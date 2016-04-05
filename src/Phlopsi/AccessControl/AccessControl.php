<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Repository\DefaultPermissionRepository;
use Propel\Permission as PropelPermission;

/**
 * Entry point for API usage
 *
 * This is the main class for interaction with the AccessControl API.
 * Every application using this API should always be accessing it through this class.
 * It handles all the creation, retrieving and deletion processes for users, roles and permissions.
 *
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class AccessControl
{
    /**
     * @var \Propel\Runtime\Configuration
     */
    private $configuration;

    /**
     * @var \Phlopsi\AccessControl\Repository\PermissionRepository
     */
    private $permissionRepository;

    /**
     * @return \Phlopsi\AccessControl\Repository\PermissionRepository
     */
    public function getPermissionRepository(): PermissionRepository
    {
        if (null === $this->permissionRepository) {
            $propelPermissionRepository = $this->configuration->getRepository(PropelPermission::class);
            $this->permissionRepository = new DefaultPermissionRepository($propelPermissionRepository);
        }

        return $this->permissionRepository;
    }

//    use Connection\ConnectionAwareTrait;
//    use TranslateExceptionsTrait;
//
//    /**
//     * Creates a new permission in the database
//     *
//     * It will throw an exception if the permission already exists or if it couldn't be created.
//     * It will return a Permission object, if the permission was successfully created.
//     *
//     * @param string $permission_id
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function createPermission(string $permission_id): Permission
//    {
//        return $this->execute(function () use ($permission_id) {
//            if (0 === strlen($permission_id)) {
//                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
//            }
//
//            $new_permission = new PropelPermission();
//            $new_permission
//                ->setExternalId($permission_id)
//                ->save($this->connection);
//
//            return new Permission($new_permission);
//        });
//    }
//
//    /**
//     * Creates a new role in the database
//     *
//     * It will throw an exception if the role already exists or if it couldn't be created.
//     * It will return a Role object, if the role was successfully created.
//     *
//     * @param string $role_id
//     *
//     * @return \Phlopsi\AccessControl\Role
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function createRole(string $role_id): Role
//    {
//        return $this->execute(function () use ($role_id) {
//            if (0 === strlen($role_id)) {
//                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
//            }
//
//            $new_role = new PropelRole();
//            $new_role
//                ->setExternalId($role_id)
//                ->save($this->connection);
//
//            return new Role($new_role);
//        });
//    }
//
//    /**
//     * Creates a new user in the database
//     *
//     * It will throw an exception if the user already exists or if it couldn't be created.
//     * It will return a User object, if the role was successfully created.
//     *
//     * @param string $user_id
//     *
//     * @return \Phlopsi\AccessControl\User
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function createUser(string $user_id): User
//    {
//        return $this->execute(function () use ($user_id) {
//            if (0 === strlen($user_id)) {
//                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
//            }
//
//            $new_user = new PropelUser();
//            $new_user
//                ->setExternalId($user_id)
//                ->save($this->connection);
//
//            return new User($new_user);
//        });
//    }
//
//    /**
//     * Deletes an existing permission from the database
//     *
//     * @param \Phlopsi\AccessControl\Permission $permission
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function deletePermission(Permission $permission)
//    {
//        return $this->execute(function () use ($permission) {
//            $propel_permission = $permission->getInternalObject();
//            $propel_permission->delete($this->connection);
//        });
//    }
//
//    /**
//     * Deletes an existing role from the database
//     *
//     * @param \Phlopsi\AccessControl\Role $role
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function deleteRole(Role $role)
//    {
//        $this->execute(function () use ($role) {
//            $propel_role = $role->getInternalObject();
//            $propel_role->delete($this->connection);
//        });
//    }
//
//    /**
//     * Deletes an existing user from the database
//     *
//     * @param \Phlopsi\AccessControl\User $user
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function deleteUser(User $user)
//    {
//        $this->execute(function () use ($user) {
//            $propel_user = $user->getInternalObject();
//            $propel_user->delete($this->connection);
//        });
//    }
//
//    /**
//     * Retrieves an existing permission from the database
//     *
//     * It will throw an exception if the permission could not be found.
//     * It will return a Permission object, if the permission was successfully retrieved.
//     *
//     * @param string $permission_id
//     *
//     * @return \Phlopsi\AccessControl\Permission
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function retrievePermission(string $permission_id): Permission
//    {
//        return $this->execute(function () use ($permission_id) {
//            if (0 === strlen($permission_id)) {
//                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
//            }
//
//            $permission = PropelPermissionQuery::create()
//                ->requireOneByExternalId($permission_id, $this->connection);
//
//            return new Permission($permission);
//        });
//    }
//
//    /**
//     * Retrieves a list of all existing permissions from the database
//     *
//     * @return string[]
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function retrievePermissionList(): array
//    {
//        return $this->execute(function () {
//            return PropelPermissionQuery::create()
//                ->select(PermissionTableMap::COL_EXTERNAL_ID)
//                ->find($this->connection)
//                ->getData();
//        });
//    }
//
//    /**
//     * Retrieves an existing role from the database
//     *
//     * It will throw an exception if the role could not be found.
//     * It will return a Role object, if the role was successfully retrieved.
//     *
//     * @param string $role_id
//     *
//     * @return \Phlopsi\AccessControl\Role
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function retrieveRole(string $role_id): Role
//    {
//        return $this->execute(function () use ($role_id) {
//            if (0 === strlen($role_id)) {
//                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
//            }
//
//            $role = PropelRoleQuery::create()
//                ->requireOneByExternalId($role_id, $this->connection);
//
//            return new Role($role);
//        });
//    }
//
//    /**
//     * Retrieves a list of all existing roles from the database
//     *
//     * @return string[]
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function retrieveRoleList(): array
//    {
//        return $this->execute(function () {
//            return PropelRoleQuery::create()
//                ->select(RoleTableMap::COL_EXTERNAL_ID)
//                ->find($this->connection)
//                ->getData();
//        });
//    }
//
//    /**
//     * Retrieves an existing user from the database
//     *
//     * It will throw an exception if the user could not be found.
//     * It will return a User object, if the user was successfully retrieved.
//     *
//     * @param string $user_id
//     *
//     * @return \Phlopsi\AccessControl\User
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function retrieveUser(string $user_id): User
//    {
//        return $this->execute(function () use ($user_id) {
//            if (0 === strlen($user_id)) {
//                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
//            }
//
//            $user = PropelUserQuery::create()
//                ->requireOneByExternalId($user_id, $this->connection);
//
//            return new User($user);
//        });
//    }
//
//    /**
//     * Retrieves a list of all existing users from the database
//     *
//     * @return string[]
//     *
//     * @throws \Phlopsi\AccessControl\Exception\Exception
//     */
//    public function retrieveUserList(): array
//    {
//        return $this->execute(function () {
//            return PropelUserQuery::create()
//                ->select(UserTableMap::COL_EXTERNAL_ID)
//                ->find($this->connection)
//                ->getData();
//        });
//    }
}
