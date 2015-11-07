<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\Role as PropelRole;
use Propel\Runtime\Connection\ConnectionInterface;

class Role
{
    use Exception\TranslateExceptionsTrait;
    
    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface|null
     */
    private $connection;

    /**
     * @var \Phlopsi\AccessControl\Propel\Role
     */
    private $role;

    /**
     * @param \Phlopsi\AccessControl\Propel\Role $role
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @codeCoverageIgnore
     */
    public function __construct(PropelRole $role, ConnectionInterface $connection = null)
    {
        $this->role = $role;
        $this->connection = $connection;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->role->getExternalId();
    }

    /**
     * @param string $permission_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function addPermission($permission_id)
    {
        $this->execute(function () use ($permission_id) {
            if (empty($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $permission = PropelPermissionQuery::create()
                ->findOneByExternalId($permission_id, $this->connection);

            if (is_null($permission)) {
                throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $permission_id));
            }

            $this->role
                ->addPermission($permission)
                ->save($this->connection);
        });
    }

    /**
     * @param string $permission_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function removePermission($permission_id)
    {
        $this->execute(function () use ($permission_id) {
            if (empty($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $permission = PropelPermissionQuery::create()
                ->findOneByExternalId($permission_id, $this->connection);

            if (is_null($permission)) {
                throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $permission_id));
            }

            $this->role
                ->removePermission($permission)
                ->save($this->connection);
        });
    }
}
