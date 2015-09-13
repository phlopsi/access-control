<?php
namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\SessionType as PropelSessionType;

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
     * @return string
     */
    public function getId()
    {
        return $this->session_type->getExternalId();
    }

    /**
     * @param string $role_id
     * @throws LengthException
     * @throws RuntimeException
     */
    public function addRole(string $role_id)
    {
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
     * @param string $role_id
     * @throws LengthException
     * @throws RuntimeException
     */
    public function removeRole(string $role_id)
    {
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
