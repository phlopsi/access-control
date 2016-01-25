<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Connection\ConnectionAware;
use Phlopsi\AccessControl\Propel\Role as PropelRole;

class Role implements ConnectionAware
{
    use Connection\ConnectionAwareTrait;
    use TranslateExceptionsTrait;

    /**
     * @var \Phlopsi\AccessControl\Propel\Role
     */
    private $role;

    /**
     * @param \Phlopsi\AccessControl\Propel\Role $role
     *
     * @codeCoverageIgnore
     */
    public function __construct(PropelRole $role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getId(): string
    {
        return $this->role->getExternalId();
    }

    /**
     * @return \Phlopsi\AccessControl\Propel\Role
     *
     * @codeCoverageIgnore
     */
    public function getInternalObject(): PropelRole
    {
        return $this->role;
    }

    /**
     * @param \Phlopsi\AccessControl\Permission $permission
     *
     * @throws \Phlopsi\AccessControl\Exception\Exception
     */
    public function addPermission(Permission $permission)
    {
        $this->execute(function () use ($permission) {
            $propel_permission = $permission->getInternalObject();

            $this->role
                ->addPermission($propel_permission)
                ->save($this->connection);
        });
    }

    /**
     * @param \Phlopsi\AccessControl\Permission $permission
     *
     * @throws \Phlopsi\AccessControl\Exception\Exception
     */
    public function removePermission(Permission $permission)
    {
        $this->execute(function () use ($permission) {
            $propel_permission = $permission->getInternalObject();

            $this->role
                ->removePermission($propel_permission)
                ->save($this->connection);
        });
    }

    /**
     * @param \Phlopsi\AccessControl\User $user
     *
     * @throws \Phlopsi\AccessControl\Exception\Exception
     */
    public function addUser(User $user)
    {
        $this->execute(function () use ($user) {
            $propel_user = $user->getInternalObject();

            $this->role
                ->addUser($propel_user)
                ->save($this->connection);
        });
    }

    /**
     * @param \Phlopsi\AccessControl\User $user
     *
     * @throws \Phlopsi\AccessControl\Exception\Exception
     */
    public function removeUser(User $user)
    {
        $this->execute(function () use ($user) {
            $propel_user = $user->getInternalObject();

            $this->role
                ->removeUser($propel_user)
                ->save($this->connection);
        });
    }
}
