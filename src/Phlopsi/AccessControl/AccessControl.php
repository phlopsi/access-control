<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Propel\Base\BasePermissionRepository;
use Phlopsi\AccessControl\Propel\Permission;
use Phlopsi\AccessControl\Repository\DefaultPermissionRepository;
use Phlopsi\AccessControl\Repository\PermissionRepository;
use Propel\Runtime\Configuration;

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
     * @var \Phlopsi\AccessControl\Propel\Base\BasePermissionRepository
     */
    private $propelPermissionRepository;

    /**
     * Factory method
     *
     * @return self
     */
    public static function fromConfiguration(Configuration $configuration)
    {
        $propelPermissionRepository = $configuration->getRepository(Permission::class);
        assert($propelPermissionRepository instanceof BasePermissionRepository);

        return new self($propelPermissionRepository);
    }

    /**
     * @param \Phlopsi\AccessControl\Propel\Base\BasePermissionRepository $propelPermissionRepository
     *
     * @codeCoverageIgnore
     */
    public function __construct(BasePermissionRepository $propelPermissionRepository)
    {
        $this->propelPermissionRepository = $propelPermissionRepository;
    }

    /**
     * @return \Phlopsi\AccessControl\Repository\PermissionRepository
     */
    public function getPermissionRepository(): PermissionRepository
    {
        return new DefaultPermissionRepository($this->propelPermissionRepository);
    }
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
