<?php
namespace phlopsi\access_control;

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
     * @var \phlopsi\access_control\AccessControl
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
     * @covers \phlopsi\access_control\AccessControl::createPermission
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testCreatePermissionInvalidArgumentException()
    {
        $this->access_control->createPermission(NULL);
    }
    
    /**
     * @covers \phlopsi\access_control\AccessControl::createPermission
     */
    public function testCreatePermission()
    {
        $this->access_control->createPermission('TEST_PERMISSION');
        $this->assertEquals(1, $this->getConnection()->getRowCount('permissions'));
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::createPermission
     * @expectedException \phlopsi\access_control\exception\RuntimeException
     */
    public function testCreatePermissionTwice()
    {
        $this->access_control->createPermission('TEST_PERMISSION');
        $this->access_control->createPermission('TEST_PERMISSION');
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::createRole
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testCreateRoleInvalidArgumentException()
    {
        $this->access_control->createRole(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::createSessionType
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testCreateSessionTypeInvalidArgumentException()
    {
        $this->access_control->createSessionType(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::createUser
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testCreateUserInvalidArgumentException()
    {
        $this->access_control->createUser(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::deletePermission
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testDeletePermissionInvalidArgumentException()
    {
        $this->access_control->deletePermission(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::deleteRole
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testDeleteRoleInvalidArgumentException()
    {
        $this->access_control->deleteRole(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::deleteSessionType
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testDeleteSessionTypeInvalidArgumentException()
    {
        $this->access_control->deleteSessionType(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::deleteUser
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testDeleteUserInvalidArgumentException()
    {
        $this->access_control->deleteUser(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::retrieveRole
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testRetrieveRoleInvalidArgumentException()
    {
        $this->access_control->retrieveRole(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::retrieveSessionType
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testRetrieveSessionTypeInvalidArgumentException()
    {
        $this->access_control->retrieveSessionType(NULL);
    }

    /**
     * @covers \phlopsi\access_control\AccessControl::retrieveUser
     * @expectedException \phlopsi\access_control\exception\InvalidArgumentException
     */
    public function testRetrieveUserInvalidArgumentException()
    {
        $this->access_control->retrieveUser(NULL);
    }

}
