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
        $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'schema.xml';
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
     * @expectedException \TypeError
     */
    public function testCreatePermissionTypeError()
    {
        $this->access_control->createPermission(null);
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
     */
    public function testCreatePermissionTwice()
    {
        $this->access_control->createPermission('TEST_PERMISSION');
        $this->access_control->createPermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole
     * @expectedException \TypeError
     */
    public function testCreateRoleTypeError()
    {
        $this->access_control->createRole(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createSessionType
     * @expectedException \TypeError
     */
    public function testCreateSessionTypeTypeError()
    {
        $this->access_control->createSessionType(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser
     * @expectedException \TypeError
     */
    public function testCreateUserTypeError()
    {
        $this->access_control->createUser(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     * @expectedException \TypeError
     */
    public function testDeletePermissionTypeError()
    {
        $this->access_control->deletePermission(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     * @expectedException \TypeError
     */
    public function testDeleteRoleTypeError()
    {
        $this->access_control->deleteRole(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     * @expectedException \TypeError
     */
    public function testDeleteSessionTypeTypeError()
    {
        $this->access_control->deleteSessionType(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     * @expectedException \TypeError
     */
    public function testDeleteUserTypeError()
    {
        $this->access_control->deleteUser(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole
     * @expectedException \TypeError
     */
    public function testRetrieveRoleTypeError()
    {
        $this->access_control->retrieveRole(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveSessionType
     * @expectedException \TypeError
     */
    public function testRetrieveSessionTypeTypeError()
    {
        $this->access_control->retrieveSessionType(null);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser
     * @expectedException \TypeError
     */
    public function testRetrieveUserTypeError()
    {
        $this->access_control->retrieveUser(null);
    }
}
