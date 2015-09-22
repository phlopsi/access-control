<?php
namespace Phlopsi\AccessControl\Test;

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

    public static function setUpBeforeClass()
    {
        self::$pdo = new PdoConnection('sqlite::memory:');

        $connection = new ConnectionWrapper(self::$pdo);

        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass('access_control', 'sqlite');
        $serviceContainer->setConnection('access_control', $connection);

        $platform = new SqlitePlatform();
        $schema_reader = new SchemaReader($platform);

        $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'schema.xml';
        $schema = $schema_reader->parseFile($file);

        $database = $schema->getDatabase('access_control');
        self::$sql = $platform->getAddTablesDDL($database);
    }

    public static function tearDownAfterClass()
    {
        self::$pdo = null;
    }
    
    private function getMockedConnection()
    {
        $mocked_connection = $this
            ->getMockBuilder(ConnectionInterface::class)
            ->getMock();

        $mocked_connection
            ->method('transaction')
            ->will($this->throwException(new \Exception));

        $mocked_connection
            ->method('prepare')
            ->will($this->throwException(new \Exception));

        $mocked_connection
            ->method('query')
            ->will($this->throwException(new \Exception));
        
        return $mocked_connection;
    }
}
