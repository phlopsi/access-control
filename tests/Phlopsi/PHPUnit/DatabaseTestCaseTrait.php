<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\PHPUnit;

use Propel\Generator\Builder\Util\SchemaReader;
use Propel\Generator\Platform\SqlitePlatform;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Connection\ConnectionWrapper;
use Propel\Runtime\Connection\PdoConnection;
use Propel\Runtime\Propel;

trait DatabaseTestCaseTrait
{
    /**
     * @var \Propel\Runtime\Connection\PdoConnection
     */
    private static $pdo;

    /**
     * @var string
     */
    private static $sql;

    /**
     * @beforeClass
     */
    public static function setUpDatabaseTestCaseBeforeClass()
    {
        self::$pdo = new PdoConnection('sqlite::memory:');

        $connection = new ConnectionWrapper(self::$pdo);

        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass('access_control', 'sqlite');
        $serviceContainer->setConnection('access_control', $connection);

        $platform = new SqlitePlatform();
        $schema_reader = new SchemaReader($platform);

        $file = \dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'schema.xml';
        $schema = $schema_reader->parseFile($file);

        $database = $schema->getDatabase('access_control');
        self::$sql = $platform->getAddTablesDDL($database);
    }

    /**
     * @afterClass
     */
    public static function tearDownDatabaseTestCaseAfterClass()
    {
        self::$pdo = null;
    }

    private function getFaultyConnection()
    {
        /** @var \PHPUnit_Extensions_Database_TestCase $this */
        $stub_connection = $this
            ->getMockBuilder(ConnectionInterface::class)
            ->getMock();

        $stub_connection
            ->method('transaction')
            ->will($this->throwException(new \Exception));

        $stub_connection
            ->method('prepare')
            ->will($this->throwException(new \Exception));

        $stub_connection
            ->method('query')
            ->will($this->throwException(new \Exception));

        return $stub_connection;
    }
}
