<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\User as PropelUser;
use Propel\Runtime\Connection\ConnectionInterface;

class User
{
    use Exception\TranslateExceptionsTrait;
    
    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface|null
     */
    private $connection;

    /**
     * @var \Phlopsi\AccessControl\Propel\User
     */
    private $user;

    /**
     * @param \Phlopsi\AccessControl\Propel\User $user
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @codeCoverageIgnore
     */
    public function __construct(PropelUser $user, ConnectionInterface $connection = null)
    {
        $this->user = $user;
        $this->connection = $connection;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->user->getExternalId();
    }

    /**
     * @param string $role_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function addRole($role_id)
    {
        $this->execute(function () use ($role_id) {
            if (empty($role_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }
        
            $role = PropelRoleQuery::create()
                ->findOneByExternalId($role_id, $this->connection);

            if (is_null($role)) {
                throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
            }

            $this->user
                ->addRole($role)
                ->save($this->connection);
        });
    }

    /**
     * @param string $permission_id
     *
     * @return bool
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     */
    public function hasPermission($permission_id)
    {
        return $this->execute(function () use ($permission_id) {
            if (empty($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }
            
            $permission = PropelPermissionQuery::create()
                ->findOneByExternalId($permission_id, $this->connection);

            if (is_null($permission)) {
                throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $permission_id));
            }

            $role_has_permission = PropelRoleQuery::create()
                ->filterByUser($this->user)
                ->filterByPermission($permission)
                ->exists($this->connection);

            return $role_has_permission;
        });
    }

    /**
     * @param string $role_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function removeRole($role_id)
    {
        $this->execute(function () use ($role_id) {
            if (empty($role_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $role = PropelRoleQuery::create()
                ->findOneByExternalId($role_id, $this->connection);

            if (is_null($role)) {
                throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
            }

            $this->user
                ->removeRole($role)
                ->save($this->connection);
        });
    }
}
