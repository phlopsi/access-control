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
     * @var ConnectionInterface|null
     */
    private $connection;

    /**
     * @var PropelRole
     */
    private $role;

    /**
     * @param PropelRole $role
     *
     * @codeCoverageIgnore
     */
    public function __construct(PropelRole $role, ConnectionInterface $connection = null)
    {
        $this->role = $role;
        $this->connection = $connection;
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
     * @throws LengthException
     * @throws RuntimeException
     */
    public function addPermission($permission_id)
    {
        if (empty($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $permission = PropelPermissionQuery::create()
                ->findOneByExternalId($permission_id, $this->connection);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($permission)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $permission_id));
        }

        $this->role
            ->addPermission($permission)
            ->save($this->connection);
    }

    /**
     * @param string $permission_id
     *
     * @throws LengthException
     * @throws RuntimeException
     */
    public function removePermission($permission_id)
    {
        if (empty($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $permission = PropelPermissionQuery::create()
                ->findOneByExternalId($permission_id, $this->connection);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($permission)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $permission_id));
        }

        $this->role
            ->removePermission($permission)
            ->save($this->connection);
    }
}
