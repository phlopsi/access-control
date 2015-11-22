<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl\Connection;

use Propel\Runtime\Connection\ConnectionInterface;

interface ConnectionAware
{
    public function setConnection(ConnectionInterface $connection);
}
