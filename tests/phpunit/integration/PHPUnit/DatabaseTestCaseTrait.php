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
     * @var \Propel\Runtime\Configuration
     */
    private static $configuration;

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
        $base_directory = \dirname(__DIR__, 3);

        $configuration_file = \implode(
            DIRECTORY_SEPARATOR,
            [$base_directory, 'build', 'config', 'propel.php']
        );

        /** @var \Propel\Runtime\Configuration $configuration */
        $configuration = require $configuration_file;

        self::$configuration = $configuration;
        self::$pdo = $configuration
            ->getConnectionManager('access_control')
            ->getWriteConnection()
            ->getWrappedConnection();

        $schema_file = $base_directory . DIRECTORY_SEPARATOR . 'schema.xml';

        $schema_reader = new SchemaReader();
        $schema_reader->setGeneratorConfig($configuration);

        $schema = $schema_reader->parseFile($schema_file);

        $database = $schema->getDatabase();
        self::$sql = (new SqlitePlatform())->getAddEntitiesDDL($database);


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
