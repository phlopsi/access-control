<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Connection\ConnectionAware;
use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\User as PropelUser;

class User implements ConnectionAware
{
    use Connection\ConnectionAwareTrait;
    use Exception\TranslateExceptionsTrait;

    /**
     * @var \Phlopsi\AccessControl\Propel\User
     */
    private $user;

    /**
     * @param \Phlopsi\AccessControl\Propel\User $user
     *
     * @codeCoverageIgnore
     */
    public function __construct(PropelUser $user)
    {
        $this->user = $user;
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
    public function addRole(string $role_id)
    {
        $this->execute(function () use ($role_id) {
            if (empty($role_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $role = PropelRoleQuery::create()
                ->requireOneByExternalId($role_id, $this->connection);

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
    public function hasPermission(string $permission_id)
    {
        return $this->execute(function () use ($permission_id) {
            if (empty($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $permission = PropelPermissionQuery::create()
                ->requireOneByExternalId($permission_id, $this->connection);

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
    public function removeRole(string $role_id)
    {
        $this->execute(function () use ($role_id) {
            if (empty($role_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $role = PropelRoleQuery::create()
                ->requireOneByExternalId($role_id, $this->connection);

            $this->user
                ->removeRole($role)
                ->save($this->connection);
        });
    }
}
