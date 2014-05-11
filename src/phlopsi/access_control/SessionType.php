<?php
namespace phlopsi\access_control;

use phlopsi\access_control\exception\InvalidArgumentException;
use phlopsi\access_control\exception\RuntimeException;
use phlopsi\access_control\propel\RoleQuery as PropelRoleQuery;
use phlopsi\access_control\propel\SessionType as PropelSessionType;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class SessionType
{
    /**
     * @var \phlopsi\access_control\propel\SessionType
     */
    private $session_type;

    public function __construct(PropelSessionType $session_type)
    {
        $this->session_type = $session_type;
    }

    public function addRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new InvalidArgumentException(InvalidArgumentException::ARGUMENT_IS_EMPTY_STRING);
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (is_null($role)) {
            throw new RuntimeException('Role "' . $role_id . '" not found!');
        }

        $this->session_type->addRole($role);
    }

    public function removeRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new InvalidArgumentException(InvalidArgumentException::ARGUMENT_IS_EMPTY_STRING);
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (is_null($role)) {
            throw new RuntimeException('Role "' . $role_id . '" not found!');
        }

        $this->session_type->removeRole($role);
    }
}
