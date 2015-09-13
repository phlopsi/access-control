<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\Permission as PropelPermission;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\Role as PropelRole;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\SessionType as PropelSessionType;
use Phlopsi\AccessControl\Propel\SessionTypeQuery as PropelSessionTypeQuery;
use Phlopsi\AccessControl\Propel\User as PropelUser;
use Phlopsi\AccessControl\Propel\UserQuery as PropelUserQuery;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class AccessControl
{
    /**
     * @param string $permission_id
     * @throws LengthException
     * @throws RuntimeException
     */
    public function createPermission(string $permission_id)
    {
        if (empty($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $new_permission = new PropelPermission();
            $new_permission->setExternalId($permission_id);
            $new_permission->save();
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }
    }

    /**
     * @param string $role_id
     * @return Role
     * @throws LengthException
     * @throws RuntimeException
     */
    public function createRole(string $role_id)
    {
        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }
        
        try {
            $new_role = new PropelRole();
            $new_role->setExternalId($role_id);
            $new_role->save();
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        return new Role($new_role);
    }

    /**
     * @param string $session_type_id
     * @return SessionType
     * @throws LengthException
     * @throws RuntimeException
     */
    public function createSessionType(string $session_type_id)
    {
        if (empty($session_type_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $new_session_type = new PropelSessionType();
            $new_session_type->setExternalId($session_type_id);
            $new_session_type->save();
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        return new SessionType($new_session_type);
    }

    /**
     * @param string $user_id
     * @return User
     * @throws LengthException
     * @throws RuntimeException
     */
    public function createUser(string $user_id)
    {
        if (empty($user_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $new_user = new PropelUser();
            $new_user->setExternalId($user_id);
            $new_user->save();
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        return new User($new_user);
    }

    /**
     * @param string $permission_id
     * @return bool
     * @throws LengthException
     * @throws RuntimeException
     */
    public function deletePermission(string $permission_id)
    {
        if (empty($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $permission = PropelPermissionQuery::create()->findOneByExternalId($permission_id);
            
            if (is_null($permission)) {
                return false;
            }
            
            $permission->delete();
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }
        
        return true;
    }

    /**
     * @param string $role_id
     * @return bool
     * @throws LengthException
     * @throws RuntimeException
     */
    public function deleteRole(string $role_id)
    {
        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $role = PropelRoleQuery::create()->findOneByExternalId($role_id);
            
            if (is_null($role)) {
                return false;
            }
            
            $role->delete();
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }
        
        return true;
    }

    /**
     * @param string $session_type_id
     * @return bool
     * @throws LengthException
     * @throws RuntimeException
     */
    public function deleteSessionType(string $session_type_id)
    {
        if (empty($session_type_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $session_type = PropelSessionTypeQuery::create()->findOneByExternalId($session_type_id);
            
            if (is_null($session_type)) {
                return false;
            }
            
            $session_type->delete();
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }
        
        return true;
    }

    /**
     * @param string $user_id
     * @return bool
     * @throws LengthException
     * @throws RuntimeException
     */
    public function deleteUser(string $user_id)
    {
        if (empty($user_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $user = PropelUserQuery::create()->findOneByExternalId($user_id);
            
            if (is_null($user)) {
                return false;
            }
            
            $user->delete();
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }
        
        return true;
    }

    /**
     * @param string $role_id
     * @return Role
     * @throws LengthException
     * @throws RuntimeException
     */
    public function retrieveRole(string $role_id)
    {
        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $role = PropelRoleQuery::create()->findOneByExternalId($role_id);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($role)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
        }

        return new Role($role);
    }

    /**
     * @param string $session_type_id
     * @return SessionType
     * @throws LengthException
     * @throws RuntimeException
     */
    public function retrieveSessionType(string $session_type_id)
    {
        if (empty($session_type_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }


        try {
            $session_type = PropelSessionTypeQuery::create()->findOneByExternalId($session_type_id);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($session_type)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $session_type_id));
        }


        return new SessionType($session_type);
    }

    /**
     * @param string $user_id
     * @return User
     * @throws LengthException
     * @throws RuntimeException
     */
    public function retrieveUser(string $user_id)
    {
        if (empty($user_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $user = PropelUserQuery::create()->findOneByExternalId($user_id);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($user)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $user_id));
        }

        return new User($user);
    }
}
