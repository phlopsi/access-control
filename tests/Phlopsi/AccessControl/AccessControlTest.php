<?php
namespace Phlopsi\AccessControl;

use Propel\Generator\Builder\Util\SchemaReader;
use Propel\Generator\Platform\SqlitePlatform;
use Propel\Generator\Util\SqlParser;
use Propel\Runtime\Connection\PdoConnection;
use Propel\Runtime\Propel;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class AccessControlTest extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var \Propel\Runtime\Connection\PdoConnection
     */
    protected static $pdo;
    
    /**
     * @var string
     */
    protected static $sql;
    
    /**
     * @var \Phlopsi\AccessControl\AccessControl
     */
    protected $access_control;
    
    /**
     * @var \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected $connection;

    public static function setUpBeforeClass()
    {
        self::$pdo = new PdoConnection('sqlite::memory:');
        
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass('access_control', 'sqlite');
        $serviceContainer->setConnection('access_control', self::$pdo);
        
        $platform = new SqlitePlatform();
        $schema_reader = new SchemaReader($platform);
        $file = __DIR__ . '/../../../schema.xml';
        $schema = $schema_reader->parseFile($file);
        $database = $schema->getDatabase('access_control');
        self::$sql = $platform->getAddTablesDDL($database);
    }

    public static function tearDownAfterClass()
    {
        self::$pdo = null;
    }

    protected function setUp()
    {
        $this->connection = $this->createDefaultDBConnection(self::$pdo);
        SqlParser::executeString(self::$sql, self::$pdo);
        $this->access_control = new AccessControl();
    }

    protected function tearDown()
    {
        $this->connection = null;
        $this->access_control = null;
    }

    protected function getConnection()
    {
        return $this->connection;
    }

    protected function getDataSet()
    {
        
    }
    
    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createPermission
     */
    public function testCreatePermission()
    {
        $this->access_control->createPermission('TEST_PERMISSION');
        $this->assertEquals(1, $this->getConnection()->getRowCount('permissions'));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createPermission
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreatePermissionTwice()
    {
        $this->access_control->createPermission('TEST_PERMISSION');
        $this->access_control->createPermission('TEST_PERMISSION');
    }
}
