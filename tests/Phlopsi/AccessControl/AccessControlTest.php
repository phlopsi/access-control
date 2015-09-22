<?php
namespace Phlopsi\AccessControl;

use Propel\Generator\Builder\Util\SchemaReader;
use Propel\Generator\Platform\SqlitePlatform;
use Propel\Generator\Util\SqlParser;
use Propel\Runtime\Connection\ConnectionInterface;
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
     * @var \Phlopsi\AccessControl\AccessControl
     */
    protected $access_control_faulty;

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

        $mock_connection = $this
            ->getMockBuilder(ConnectionInterface::class)
            ->getMock();

        $mock_connection
            ->method('transaction')
            ->will($this->throwException(new \Exception));

        $mock_connection
            ->method('prepare')
            ->will($this->throwException(new \Exception));

        $mock_connection
            ->method('query')
            ->will($this->throwException(new \Exception));

        $this->access_control_faulty = new AccessControl($mock_connection);
    }

    protected function tearDown()
    {
        $this->connection = null;
        $this->access_control = null;
        $this->access_control_faulty = null;
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
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreatePermissionException()
    {
        $this->access_control_faulty->createPermission('TEST_PERMISSION');
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
    public function testDeletePermissionWithInvalidId()
    {
        $result = $this->access_control->deletePermission('TEST_PERMISSION');
        $this->assertFalse($result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testDeletePermissionException()
    {
        $this->access_control_faulty->deletePermission('TEST_PERMISSION');
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
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreateRoleException()
    {
        $this->access_control_faulty->createRole('TEST_ROLE');
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
    public function testDeleteRoleWithInvalidId()
    {
        $result = $this->access_control->deleteRole('TEST_ROLE');
        $this->assertFalse($result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testDeleteRoleException()
    {
        $this->access_control_faulty->deleteRole('TEST_ROLE');
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

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRetrieveRoleWithEmptyId()
    {
        $this->access_control->retrieveRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveRoleWithInvalidId()
    {
        $this->access_control->retrieveRole('TEST_ROLE');
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole
     * @uses \Phlopsi\AccessControl\AccessControl::createRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveRoleException()
    {
        try {
            $this->access_control->createRole('TEST_ROLE');
        } catch (\Exception $exception) {
            $this->fail($exception->getTraceAsString());
        }

        $this->access_control_faulty->retrieveRole('TEST_ROLE');
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole
     * @uses \Phlopsi\AccessControl\AccessControl::createRole
     * @uses \Phlopsi\AccessControl\Role::getId
     */
    public function testRetrieveRole()
    {
        $this->access_control->createRole('TEST_ROLE');
        $role = $this->access_control->retrieveRole('TEST_ROLE');
        $this->assertInstanceOf(\Phlopsi\AccessControl\Role::class, $role);
        $this->assertEquals('TEST_ROLE', $role->getId());
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testCreateSessionTypeWithEmptyId()
    {
        $this->access_control->createSessionType('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreateSessionTypeException()
    {
        $this->access_control_faulty->createSessionType('TEST_SESSION_TYPE');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createSessionType
     */
    public function testCreateSessionType()
    {
        $session_type = $this->access_control->createSessionType('TEST_SESSION_TYPE');
        $this->assertInstanceOf(\Phlopsi\AccessControl\SessionType::class, $session_type);
        $this->assertEquals(1, $this->getConnection()->getRowCount('session_types'));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testDeleteSessionTypeWithEmptyId()
    {
        $this->access_control->deleteSessionType('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     */
    public function testDeleteSessionTypeWithInvalidId()
    {
        $result = $this->access_control->deleteSessionType('TEST_SESSION_TYPE');
        $this->assertFalse($result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testDeleteSessionTypeException()
    {
        $this->access_control_faulty->deleteSessionType('TEST_SESSION_TYPE');
    }

    /**
     * @depends testCreateSessionType
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     * @uses \Phlopsi\AccessControl\AccessControl::createSessionType
     */
    public function testDeleteSessionType()
    {
        $this->access_control->createSessionType('TEST_SESSION_TYPE');
        $result = $this->access_control->deleteSessionType('TEST_SESSION_TYPE');
        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount('session_types'));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRetrieveSessionTypeWithEmptyId()
    {
        $this->access_control->retrieveSessionType('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveSessionTypeWithInvalidId()
    {
        $this->access_control->retrieveSessionType('TEST_SESSION_TYPE');
    }

    /**
     * @depends testCreateSessionType
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveSessionType
     * @uses \Phlopsi\AccessControl\AccessControl::createSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveSessionTypeException()
    {
        try {
            $this->access_control->createSessionType('TEST_SESSION_TYPE');
        } catch (\Exception $exception) {
            $this->fail($exception->getTraceAsString());
        }

        $this->access_control_faulty->retrieveSessionType('TEST_SESSION_TYPE');
    }

    /**
     * @depends testCreateSessionType
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveSessionType
     * @uses \Phlopsi\AccessControl\AccessControl::createSessionType
     * @uses \Phlopsi\AccessControl\SessionType::getId
     */
    public function testRetrieveSessionType()
    {
        $this->access_control->createSessionType('TEST_SESSION_TYPE');
        $sesssion_type = $this->access_control->retrieveSessionType('TEST_SESSION_TYPE');
        $this->assertInstanceOf(\Phlopsi\AccessControl\SessionType::class, $sesssion_type);
        $this->assertEquals('TEST_SESSION_TYPE', $sesssion_type->getId());
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testCreateUserWithEmptyId()
    {
        $this->access_control->createUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreateUserException()
    {
        $this->access_control_faulty->createUser('TEST_USER');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser
     */
    public function testCreateUser()
    {
        $user = $this->access_control->createUser('TEST_USER');
        $this->assertInstanceOf(\Phlopsi\AccessControl\User::class, $user);
        $this->assertEquals(1, $this->getConnection()->getRowCount('users'));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testDeleteUserWithEmptyId()
    {
        $this->access_control->deleteUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     */
    public function testDeleteUserWithInvalidId()
    {
        $result = $this->access_control->deleteUser('TEST_USER');
        $this->assertFalse($result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testDeleteUserException()
    {
        $this->access_control_faulty->deleteUser('TEST_USER');
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     * @uses \Phlopsi\AccessControl\AccessControl::createUser
     */
    public function testDeleteUser()
    {
        $this->access_control->createUser('TEST_USER');
        $result = $this->access_control->deleteUser('TEST_USER');
        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount('users'));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRetrieveUserWithEmptyId()
    {
        $this->access_control->retrieveUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveUserWithInvalidId()
    {
        $this->access_control->retrieveUser('TEST_USER');
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser
     * @uses \Phlopsi\AccessControl\AccessControl::createUser
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveUserException()
    {
        try {
            $this->access_control->createUser('TEST_USER');
        } catch (\Exception $exception) {
            $this->fail($exception->getTraceAsString());
        }

        $this->access_control_faulty->retrieveUser('TEST_USER');
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser
     * @uses \Phlopsi\AccessControl\AccessControl::createUser
     * @uses \Phlopsi\AccessControl\User::getId
     */
    public function testRetrieveUser()
    {
        $this->access_control->createUser('TEST_USER');
        $user = $this->access_control->retrieveUser('TEST_USER');
        $this->assertInstanceOf(\Phlopsi\AccessControl\User::class, $user);
        $this->assertEquals('TEST_USER', $user->getId());
    }
}
