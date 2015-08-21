<?php
namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\User as PropelUser;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class User
{
    /**
     * @var PropelUser
     */
    private $user;

    /**
     * @param PropelUser $user
     */
    public function __construct(PropelUser $user)
    {
        $this->user = $user;
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

        $this->role->addPermission($role);
    }

    /**
     * @param string $permission_id
     * @return boolean
     * @throws LengthException
     */
    public function hasPermission(string $permission_id)
    {
        if (empty($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
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

        $this->user->removeRole($role);
    }
}
