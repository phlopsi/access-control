<?php
namespace phlopsi\access_control;

use phlopsi\access_control\propel\PermissionQuery as PropelPermissionQuery;
use phlopsi\access_control\propel\RoleQuery as PropelRoleQuery;
use phlopsi\access_control\propel\User as PropelUser;

class User
{
    private $user;

    public function __construct(PropelUser $user)
    {
        $this->user = $user;
    }

    public function addRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new \InvalidArgumentException('$role_id converts to an empty string!');
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (is_null($role)) {
            throw new EntityNotFoundException('Role "' . $role_id . '" not found!');
        }

        $this->role->addPermission($role);
    }

    public function hasPermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        if (empty($permission_id)) {
            throw new \InvalidArgumentException('$permission_id converts to an empty string!');
        }

        //TODO more efficiency!
        $roles = $this->user->getRoles();

        foreach ($roles as $role) {
            $permission = PropelPermissionQuery::create()
                ->filterByRole($role)
                ->findByExternalId($permission_id);

            if (!is_null($permission)) {
                return true;
            }

            $descendant_roles = $role->getDescendants();

            foreach ($descendant_roles as $descendant_role) {
                $permission = PropelPermissionQuery::create()
                    ->filterByRole($descendant_role)
                    ->findByExternalId($permission_id);

                if (!is_null($permission)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function removeRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new \InvalidArgumentException('$role_id converts to an empty string!');
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (is_null($role)) {
            throw new EntityNotFoundException('Role "' . $role_id . '" not found!');
        }

        $this->user->removeRole($role);
    }
}
