<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Connection\ConnectionAware;
use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\UserQuery as PropelUserQuery;
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
     * @param string $permission_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function addPermission(string $permission_id)
    {
        $this->execute(function () use ($permission_id) {
            if (0 === strlen($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $permission = PropelPermissionQuery::create()
                ->requireOneByExternalId($permission_id, $this->connection);

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
    public function removePermission(string $permission_id)
    {
        $this->execute(function () use ($permission_id) {
            if (0 === strlen($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $permission = PropelPermissionQuery::create()
                ->requireOneByExternalId($permission_id, $this->connection);

            $this->role
                ->removePermission($permission)
                ->save($this->connection);
        });
    }

    /**
     * @param string $user_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function addUser(string $user_id)
    {
        $this->execute(function () use ($user_id) {
            if (0 === strlen($user_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $user = PropelUserQuery::create()
                ->requireOneByExternalId($user_id, $this->connection);

            $this->role
                ->addUser($user)
                ->save($this->connection);
        });
    }

    /**
     * @param string $user_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function removeUser(string $user_id)
    {
        $this->execute(function () use ($user_id) {
            if (0 === strlen($user_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $user = PropelUserQuery::create()
                ->requireOneByExternalId($user_id, $this->connection);

            $this->role
                ->removeUser($user)
                ->save($this->connection);
        });
    }
}
