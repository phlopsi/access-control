<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl\Connection;

use Propel\Runtime\Connection\ConnectionInterface;

interface ConnectionAware
{
    public function setConnection(ConnectionInterface $connection);
}
