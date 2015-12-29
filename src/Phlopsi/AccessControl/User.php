<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

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
    public function getId(): string
    {
        return $this->user->getExternalId();
    }

    /**
     * @param string $permission_id
     *
     * @return bool
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     */
    public function hasPermission(string $permission_id): bool
    {
        return $this->execute(function () use ($permission_id) {
            if (0 === strlen($permission_id)) {
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
}
