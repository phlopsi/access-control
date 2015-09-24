<?php
namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\RoleQuery as PropelRoleQuery;
use Phlopsi\AccessControl\Propel\SessionType as PropelSessionType;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class SessionType
{
    /**
     * @var ConnectionInterface|null
     */
    private $connection;

    /**
     * @var PropelSessionType
     */
    private $session_type;

    /**
     * @param PropelSessionType $session_type
     *
     * @codeCoverageIgnore
     */
    public function __construct(PropelSessionType $session_type, ConnectionInterface $connection = null)
    {
        $this->session_type = $session_type;
        $this->connection = $connection;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->session_type->getExternalId();
    }

    /**
     * @param string $role_id
     *
     * @throws LengthException
     * @throws RuntimeException
     */
    public function addRole($role_id)
    {
        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $role = PropelRoleQuery::create()
                ->findOneByExternalId($role_id, $this->connection);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($role)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
        }

        $this->session_type
            ->addRole($role)
            ->save($this->connection);
    }

    /**
     * @param string $role_id
     *
     * @throws LengthException
     * @throws RuntimeException
     */
    public function removeRole($role_id)
    {
        if (empty($role_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        try {
            $role = PropelRoleQuery::create()
                ->findOneByExternalId($role_id, $this->connection);
        } catch (\Exception $exception) {
            throw new RuntimeException('', 0, $exception);
        }

        if (is_null($role)) {
            throw new RuntimeException(sprintf(RuntimeException::ENTITY_NOT_FOUND, $role_id));
        }

        $this->session_type
            ->removeRole($role)
            ->save($this->connection);
    }
}
