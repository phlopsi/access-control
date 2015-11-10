<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl\Connection;

use Propel\Runtime\Connection\ConnectionInterface;

trait ConnectionAwareTrait
{
    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface|null
     */
    private $connection;

    /**
     * @param ConnectionInterface $connection
     *
     * @codeCoverageIgnore
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }
}
