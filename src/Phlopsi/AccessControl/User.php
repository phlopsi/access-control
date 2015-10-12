<?php
namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\User as PropelUser;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class User
{
    /**
     * @var ConnectionInterface|null
     */
    private $connection;

    /**
     * @var PropelUser
     */
    private $user;

    /**
     * @param PropelUser $user
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
     * @throws LengthException
     * @throws RuntimeException
     */
    public function addRole($role_id)
    {
        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $role = PropelRoleQuery::create()
                ->findOneByExternalId($role_id, $this->connection);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($role)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
        }

        try {
            $this->user
                ->addRole($role)
                ->save($this->connection);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }
    }

    /**
     * @param string $permission_id
     *
     * @return boolean
     *
     * @throws LengthException
     */
    public function hasPermission($permission_id)
    {
        if (empty($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            //TODO more efficiency!
            $roles = $this->user->getRoles(null, $this->connection);

            foreach ($roles as $role) {
                $permission = PropelPermissionQuery::create()
                    ->filterByRole($role)
                    ->findByExternalId($permission_id, $this->connection);

                if (!is_null($permission)) {
                    return true;
                }

                $descendant_roles = $role->getDescendants(null, $this->connection);

                foreach ($descendant_roles as $descendant_role) {
                    $permission = PropelPermissionQuery::create()
                        ->filterByRole($descendant_role)
                        ->findByExternalId($permission_id, $this->connection);

                    if (!is_null($permission)) {
                        return true;
                    }
                }
            }
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        return false;
    }

    /**
     * @param string $role_id
     *
     * @throws LengthException
     * @throws RuntimeException
     */
    public function removeRole($role_id)
    {
        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $role = PropelRoleQuery::create()
                ->findOneByExternalId($role_id, $this->connection);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($role)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
        }

        try {
            $this->user
                ->removeRole($role)
                ->save($this->connection);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }
    }
}
