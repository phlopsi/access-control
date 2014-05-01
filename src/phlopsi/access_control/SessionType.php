<?php
namespace phlopsi\access_control;

use phlopsi\access_control\propel\RoleQuery as PropelRoleQuery;
use phlopsi\access_control\propel\SessionType as PropelSessionType;

class SessionType
{
    private $session_type;

    public function __construct(PropelSessionType $session_type)
    {
        $this->session_type = $session_type;
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

        $this->session_type->addRole($role);
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

        $this->session_type->removeRole($role);
    }
}
