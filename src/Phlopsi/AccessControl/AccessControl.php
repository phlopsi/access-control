<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Connection\ConnectionAware;
use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Propel\Map\PermissionTableMap;
use Phlopsi\AccessControl\Propel\Map\RoleTableMap;
use Phlopsi\AccessControl\Propel\Map\UserTableMap;
use Phlopsi\AccessControl\Propel\Permission as PropelPermission;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\Role as PropelRole;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\User as PropelUser;
use Phlopsi\AccessControl\Propel\UserQuery as PropelUserQuery;

/**
 * Entry point for API usage
 *
 * This is the main class for interaction with the AccessControl API.
 * Every application using this API should always be accessing it through this class.
 * It handles all the creation, retrieving and deletion processes for users, roles and permissions.
 */
class AccessControl implements ConnectionAware
{
    use Connection\ConnectionAwareTrait;
    use TranslateExceptionsTrait;

    /**
     * Creates a new permission in the database
     *
     * It will throw an exception if the permission already exists or if it couldn't be created.
     * It will return a Permission object, if the permission was successfully created.
     *
     * @param string $permission_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function createPermission(string $permission_id): Permission
    {
        return $this->execute(function () use ($permission_id) {
            if (0 === strlen($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $new_permission = new PropelPermission();
            $new_permission
                ->setExternalId($permission_id)
                ->save($this->connection);

            return new Permission($new_permission);
        });
    }

    /**
     * Creates a new role in the database
     *
     * It will throw an exception if the role already exists or if it couldn't be created.
     * It will return a Role object, if the role was successfully created.
     *
     * @param string $role_id
     *
     * @return \Phlopsi\AccessControl\Role
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function createRole(string $role_id): Role
    {
        return $this->execute(function () use ($role_id) {
            if (0 === strlen($role_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $new_role = new PropelRole();
            $new_role
                ->setExternalId($role_id)
                ->save($this->connection);

            return new Role($new_role);
        });
    }

    /**
     * Creates a new user in the database
     *
     * It will throw an exception if the user already exists or if it couldn't be created.
     * It will return a User object, if the role was successfully created.
     *
     * @param string $user_id
     *
     * @return \Phlopsi\AccessControl\User
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function createUser(string $user_id): User
    {
        return $this->execute(function () use ($user_id) {
            if (0 === strlen($user_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $new_user = new PropelUser();
            $new_user
                ->setExternalId($user_id)
                ->save($this->connection);

            return new User($new_user);
        });
    }

    /**
     * Deletes an existing permission from the database
     *
     * It will return true, if the permission was successfully deleted or false, if it could not be found.
     *
     * @param string $permission_id
     *
     * @return bool
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function deletePermission(string $permission_id): bool
    {
        return $this->execute(function () use ($permission_id) {
            if (0 === strlen($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $permission = PropelPermissionQuery::create()
                ->findOneByExternalId($permission_id, $this->connection);

            if (is_null($permission)) {
                return false;
            }

            $permission->delete($this->connection);

            return true;
        });
    }

    /**
     * Deletes an existing role from the database
     *
     * It will return true, if the role was successfully deleted or false, if it could not be found.
     *
     * @param string $role_id
     *
     * @return bool
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function deleteRole(string $role_id): bool
    {
        return $this->execute(function () use ($role_id) {
            if (0 === strlen($role_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $role = PropelRoleQuery::create()
                ->findOneByExternalId($role_id, $this->connection);

            if (is_null($role)) {
                return false;
            }

            $role->delete($this->connection);

            return true;
        });
    }

    /**
     * Deletes an existing user from the database
     *
     * It will return true, if the user was successfully deleted or false, if it could not be found.
     *
     * @param string $user_id
     *
     * @return bool
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function deleteUser(string $user_id): bool
    {
        return $this->execute(function () use ($user_id) {
            if (0 === strlen($user_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $user = PropelUserQuery::create()
                ->findOneByExternalId($user_id, $this->connection);

            if (is_null($user)) {
                return false;
            }

            $user->delete($this->connection);

            return true;
        });
    }

    /**
     * Retrieves a list of all existing permissions from the database
     *
     * @return string[]
     *
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function retrievePermissionList(): array
    {
        return $this->execute(function () {
            return PropelPermissionQuery::create()
                ->select(PermissionTableMap::COL_EXTERNAL_ID)
                ->find($this->connection)
                ->getData();
        });
    }

    /**
     * Retrieves an existing role from the database
     *
     * It will throw an exception if the role could not be found.
     * It will return a Role object, if the role was successfully retrieved.
     *
     * @param string $role_id
     *
     * @return \Phlopsi\AccessControl\Role
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function retrieveRole(string $role_id): Role
    {
        return $this->execute(function () use ($role_id) {
            if (0 === strlen($role_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $role = PropelRoleQuery::create()
                ->requireOneByExternalId($role_id, $this->connection);

            return new Role($role);
        });
    }

    /**
     * Retrieves a list of all existing roles from the database
     *
     * @return string[]
     *
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function retrieveRoleList(): array
    {
        return $this->execute(function () {
            return PropelRoleQuery::create()
                ->select(RoleTableMap::COL_EXTERNAL_ID)
                ->find($this->connection)
                ->getData();
        });
    }

    /**
     * Retrieves an existing user from the database
     *
     * It will throw an exception if the user could not be found.
     * It will return a User object, if the user was successfully retrieved.
     *
     * @param string $user_id
     *
     * @return \Phlopsi\AccessControl\User
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function retrieveUser(string $user_id): User
    {
        return $this->execute(function () use ($user_id) {
            if (0 === strlen($user_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $user = PropelUserQuery::create()
                ->requireOneByExternalId($user_id, $this->connection);

            return new User($user);
        });
    }

    /**
     * Retrieves a list of all existing users from the database
     *
     * @return string[]
     *
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function retrieveUserList(): array
    {
        return $this->execute(function () {
            return PropelUserQuery::create()
                ->select(UserTableMap::COL_EXTERNAL_ID)
                ->find($this->connection)
                ->getData();
        });
    }
}
