<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl\Entity;

use Phlopsi\AccessControl\Connection\ConnectionAware;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\User as PropelUser;

class User implements ConnectionAware
{
    use \Phlopsi\AccessControl\Connection\ConnectionAwareTrait;
    use \Phlopsi\AccessControl\TranslateExceptionsTrait;

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
    public function getId(): string
    {
        return $this->user->getExternalId();
    }

    /**
     * @return \Phlopsi\AccessControl\Propel\User
     *
     * @codeCoverageIgnore
     */
    public function getInternalObject(): PropelUser
    {
        return $this->user;
    }

    /**
     * @param \Phlopsi\AccessControl\Permission $permission
     *
     * @return bool
     *
     * @throws \Phlopsi\AccessControl\Exception\Exception
     */
    public function hasPermission(Permission $permission): bool
    {
        return $this->execute(function () use ($permission) {
            $propel_permission = $permission->getInternalObject();

            $role_has_permission = PropelRoleQuery::create()
                ->filterByUser($this->user)
                ->filterByPermission($propel_permission)
                ->exists($this->connection);

            return $role_has_permission;
        });
    }
}
