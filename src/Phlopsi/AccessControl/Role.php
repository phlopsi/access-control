<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Connection\ConnectionAware;
use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use Phlopsi\AccessControl\Propel\Role as PropelRole;

class Role implements ConnectionAware
{
    use Connection\ConnectionAwareTrait;
    use Exception\TranslateExceptionsTrait;

    /**
     * @var \Phlopsi\AccessControl\Propel\Role
     */
    private $role;

    /**
     * @param \Phlopsi\AccessControl\Propel\Role $role
     *
     * @codeCoverageIgnore
     */
    public function __construct(PropelRole $role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->role->getExternalId();
    }

    /**
     * @param string $permission_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function addPermission($permission_id)
    {
        $this->execute(function () use ($permission_id) {
            if (empty($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $permission = PropelPermissionQuery::create()
                ->requireOneByExternalId($permission_id, $this->connection);

            $this->role
                ->addPermission($permission)
                ->save($this->connection);
        });
    }

    /**
     * @param string $permission_id
     *
     * @throws \Phlopsi\AccessControl\Exception\LengthException
     * @throws \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function removePermission($permission_id)
    {
        $this->execute(function () use ($permission_id) {
            if (empty($permission_id)) {
                throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
            }

            $permission = PropelPermissionQuery::create()
                ->requireOneByExternalId($permission_id, $this->connection);

            $this->role
                ->removePermission($permission)
                ->save($this->connection);
        });
    }
}
