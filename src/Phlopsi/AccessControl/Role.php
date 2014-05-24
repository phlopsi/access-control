<?php
namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\Role as PropelRole;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class Role
{
    /**
     * @var PropelRole
     */
    private $role;

    /**
     * @param PropelRole $role
     */
    public function __construct(PropelRole $role)
    {
        $this->role = $role;
    }

    /**
     * @param mixed $permission_id
     * @throws LengthException
     * @throws RuntimeException
     */
    public function addPermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        if (empty($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        $permission = PropelPermissionQuery::create()->findOneByExternalId($permission_id);

        if (is_null($permission)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $permission_id));
        }

        $this->role->addPermission($permission);
    }

    /**
     * @param mixed $permission_id
     * @throws LengthException
     * @throws RuntimeException
     */
    public function removePermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        if (empty($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        $permission = PropelPermissionQuery::create()->findOneByExternalId($permission_id);

        if (is_null($permission)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $permission_id));
        }

        $this->role->removePermission($permission);
    }
}
