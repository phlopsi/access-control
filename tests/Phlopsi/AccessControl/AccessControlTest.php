<?php
namespace Phlopsi\AccessControl;

use Propel\Generator\Builder\Util\SchemaReader;
use Propel\Generator\Platform\SqlitePlatform;
use Propel\Generator\Util\SqlParser;
use Propel\Runtime\Connection\ConnectionWrapper;
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
        
        $connection = new ConnectionWrapper(self::$pdo);
        
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass('access_control', 'sqlite');
        $serviceContainer->setConnection('access_control', $connection);
        
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
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testCreatePermissionWithEmptyId()
    {
        $this->access_control->createPermission('');
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
     * @depends testCreatePermission
     * @covers \Phlopsi\AccessControl\AccessControl::createPermission
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreatePermissionTwice()
    {
        $this->access_control->createPermission('TEST_PERMISSION');
        $this->access_control->createPermission('TEST_PERMISSION');
    }
    
    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testDeletePermissionWithEmptyId()
    {
        $this->access_control->deletePermission('');
    }
    
    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     */
    public function testDeleteNonexistentPermission()
    {
        $result = $this->access_control->deletePermission('TEST_PERMISSION');
        $this->assertFalse($result);
    }
    
    /**
     * @depends testCreatePermission
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     * @uses \Phlopsi\AccessControl\AccessControl::createPermission
     */
    public function testDeletePermission()
    {
        $this->access_control->createPermission('TEST_PERMISSION');
        $result = $this->access_control->deletePermission('TEST_PERMISSION');
        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount('permissions'));
    }
    
    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testCreateRoleWithEmptyId()
    {
        $this->access_control->createRole('');
    }
    
    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole
     */
    public function testCreateRole()
    {
        $role = $this->access_control->createRole('TEST_ROLE');
        $this->assertInstanceOf(\Phlopsi\AccessControl\Role::class, $role);
        $this->assertEquals(1, $this->getConnection()->getRowCount('roles'));
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::createRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreateRoleTwice()
    {
        $this->access_control->createRole('TEST_ROLE');
        $this->access_control->createRole('TEST_ROLE');
    }
    
    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testDeleteRoleWithEmptyId()
    {
        $this->access_control->deleteRole('');
    }
    
    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     */
    public function testDeleteNonexistentRole()
    {
        $result = $this->access_control->deleteRole('TEST_ROLE');
        $this->assertFalse($result);
    }
    
    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     * @uses \Phlopsi\AccessControl\AccessControl::createRole
     */
    public function testDeleteRole()
    {
        $this->access_control->createRole('TEST_ROLE');
        $result = $this->access_control->deleteRole('TEST_ROLE');
        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount('roles'));
    }
}
