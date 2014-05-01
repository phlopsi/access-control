<?php
namespace phlopsi\access_control;

use phlopsi\access_control\propel\PermissionQuery as PropelPermissionQuery;
use phlopsi\access_control\propel\Role as PropelRole;

class Role
{
    private $role;

    public function __construct(PropelRole $role)
    {
        $this->role = $role;
    }

    public function addPermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        if (empty($permission_id)) {
            throw new \InvalidArgumentException('$permission_id converts to an empty string!');
        }

        $permission = PropelPermissionQuery::create()->findOneByExternalId($permission_id);

        if (is_null($permission)) {
            throw new EntityNotFoundException('Permission "' . $permission_id . '" not found!');
        }

        $this->role->addPermission($permission);
    }

    public function removePermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        if (empty($permission_id)) {
            throw new \InvalidArgumentException('$permission_id converts to an empty string!');
        }

        $permission = PropelPermissionQuery::create()->findOneByExternalId($permission_id);

        if (is_null($permission)) {
            throw new EntityNotFoundException('Permission "' . $permission_id . '" not found!');
        }

        $this->role->removePermission($permission);
    }
}
