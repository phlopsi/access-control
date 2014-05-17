<?php
namespace phlopsi\access_control;

use phlopsi\access_control\exception\LengthException;
use phlopsi\access_control\exception\RuntimeException;
use phlopsi\access_control\propel\RoleQuery as PropelRoleQuery;
use phlopsi\access_control\propel\SessionType as PropelSessionType;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class SessionType
{
    /**
     * @var PropelSessionType
     */
    private $session_type;

    /**
     * @param PropelSessionType $session_type
     */
    public function __construct(PropelSessionType $session_type)
    {
        $this->session_type = $session_type;
    }

    /**
     * @param mixed $role_id
     * @throws LengthException
     * @throws RuntimeException
     */
    public function addRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (is_null($role)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
        }

        $this->session_type->addRole($role);
    }

    /**
     * @param mixed $role_id
     * @throws LengthException
     * @throws RuntimeException
     */
    public function removeRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (is_null($role)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
        }

        $this->session_type->removeRole($role);
    }
}
