<?php
/*
 * The MIT License
 *
 * Copyright 2014 Patrick Fischer.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace org\bitbucket\phlopsi\access_control;

use org\bitbucket\phlopsi\access_control\propel\Permission as PropelPermission;
use org\bitbucket\phlopsi\access_control\propel\PermissionQuery as PropelPermissionQuery;
use org\bitbucket\phlopsi\access_control\propel\Role as PropelRole;
use org\bitbucket\phlopsi\access_control\propel\RoleQuery as PropelRoleQuery;
use org\bitbucket\phlopsi\access_control\propel\User as PropelUser;
use org\bitbucket\phlopsi\access_control\propel\UserQuery as PropelUserQuery;

class AccessControl
{
    private $roles = [];
    private $users = [];

    public function createPermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        if (empty($permission_id)) {
            throw new \InvalidArgumentException('$permission_id converts to an empty string!');
        }

        $permission = PropelPermissionQuery::create()->findOneByExternalId($permission_id);

        if (!is_null($permission)) {
            throw new EntityAlreadyExistsException('Permission "' . $permission_id . '" already exists!');
        }

        $new_permission = new PropelPermission();
        $new_permission->setExternalId($permission_id);
        $new_permission->save();
    }

    public function createRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new \InvalidArgumentException('$role_id converts to an empty string!');
        }

        if (\array_key_exists($role_id, $this->roles)) {
            throw new EntityAlreadyExistsException('Role "' . $role_id . '" already exists!');
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (!is_null($role)) {
            throw new EntityAlreadyExistsException('Role "' . $role_id . '" already exists!');
        }

        $new_role = new PropelRole();
        $new_role->setExternalId($role_id);
        $new_role->save();

        $this->roles[$role_id] = new Role($new_role);

        return $this->roles[$role_id];
    }

    public function createUser($user_id)
    {
        $user_id = (string) $user_id;

        if (empty($user_id)) {
            throw new \InvalidArgumentException('$user_id converts to an empty string!');
        }

        if (\array_key_exists($user_id, $this->users)) {
            throw new EntityAlreadyExistsException('User "' . $user_id . '" already exists!');
        }

        $user = PropelUserQuery::create()->findOneByExternalId($user_id);

        if (!is_null($user)) {
            throw new EntityAlreadyExistsException('User "' . $user_id . '" already exists!');
        }

        $new_user = new PropelUser();
        $new_user->setExternalId($user_id);
        $new_user->save();

        $this->users[$user_id] = new User($new_user);

        return $this->users[$user_id];
    }

    public function deletePermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        if (empty($permission_id)) {
            throw new \InvalidArgumentException('$permission_id converts to an empty string!');
        }

        PropelPermissionQuery::create()
            ->findOneByExternalId($permission_id)
            ->delete();
    }

    public function deleteRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new \InvalidArgumentException('$role_id converts to an empty string!');
        }

        if (\array_key_exists($role_id, $this->roles)) {
            unset($this->roles[$role_id]);
        }

        PropelRoleQuery::create()
            ->findOneByExternalId($role_id)
            ->delete();
    }

    public function deleteUser($user_id)
    {
        $user_id = (string) $user_id;

        if (empty($user_id)) {
            throw new \InvalidArgumentException('$user_id converts to an empty string!');
        }

        if (\array_key_exists($user_id, $this->users)) {
            unset($this->users[$user_id]);
        }

        PropelUserQuery::create()
            ->findOneByExternalId($user_id)
            ->delete();
    }

    public function retrieveRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new \InvalidArgumentException('$role_id converts to an empty string!');
        }

        if (!\array_key_exists($role_id, $this->roles)) {
            $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

            if (is_null($role)) {
                throw new EntityNotFoundException('Role "' . $role_id . '" not found!');
            }

            $this->roles[$role_id] = new Role($role);
        }

        return $this->roles[$role_id];
    }

    public function retrieveUser($user_id)
    {
        $user_id = (string) $user_id;

        if (empty($user_id)) {
            throw new \InvalidArgumentException('$user_id converts to an empty string!');
        }

        if (!\array_key_exists($user_id, $this->users)) {
            $user = PropelUserQuery::create()->findOneByExternalId($user_id);

            if (is_null($user)) {
                throw new EntityNotFoundException('User "' . $user_id . '" not found!');
            }

            $this->users[$user_id] = new User($user);
        }

        return $this->users[$user_id];
    }

}
