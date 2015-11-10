<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Connection\ConnectionAware;
use Phlopsi\AccessControl\Exception\LengthException;
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
    use Exception\TranslateExceptionsTrait;

    /**
     * Creates a new permission in the database
     *
     * It will throw an exception if the permission already exists or if it couldn't be created.
     *
     * @param string $permission_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function createPermission($permission_id)
    {
        $this->execute(function () use ($permission_id) {
            if (empty($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $new_permission = new PropelPermission();
            $new_permission
                ->setExternalId($permission_id)
                ->save($this->connection);
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
    public function createRole($role_id)
    {
        return $this->execute(function () use ($role_id) {
            if (empty($role_id)) {
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
    public function createUser($user_id)
    {
        return $this->execute(function () use ($user_id) {
            if (empty($user_id)) {
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
    public function deletePermission($permission_id)
    {
        return $this->execute(function () use ($permission_id) {
            if (empty($permission_id)) {
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
    public function deleteRole($role_id)
    {
        return $this->execute(function () use ($role_id) {
            if (empty($role_id)) {
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
    public function deleteUser($user_id)
    {
        return $this->execute(function () use ($user_id) {
            if (empty($user_id)) {
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
    public function retrieveRole($role_id)
    {
        return $this->execute(function () use ($role_id) {
            if (empty($role_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $role = PropelRoleQuery::create()
                ->requireOneByExternalId($role_id, $this->connection);

            return new Role($role);
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
    public function retrieveUser($user_id)
    {
        return $this->execute(function () use ($user_id) {
            if (empty($user_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $user = PropelUserQuery::create()
                ->requireOneByExternalId($user_id, $this->connection);

            return new User($user);
        });
    }
}
