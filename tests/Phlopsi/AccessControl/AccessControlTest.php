<?php
namespace Phlopsi\AccessControl;

use Propel\Generator\Util\SqlParser;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class AccessControlTest extends \PHPUnit_Extensions_Database_TestCase
{
    use Test\DatabaseTestCaseTrait;

    /**
     * @var AccessControl
     */
    private $access_control;

    /**
     * @var AccessControl
     */
    private $access_control_faulty;

    /**
     * @var \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    private $connection;

    protected function setUp()
    {
        $this->connection = $this->createDefaultDBConnection(self::$pdo);

        SqlParser::executeString(self::$sql, self::$pdo);

        $this->access_control = new AccessControl();
        $this->access_control_faulty = new AccessControl($this->getFaultyConnection());
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
        // Act
        $this->access_control->createPermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createPermission
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreatePermissionException()
    {
        // Act
        $this->access_control_faulty->createPermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createPermission
     */
    public function testCreatePermission()
    {
        // Act
        $this->access_control->createPermission('TEST_PERMISSION');

        // Assert
        $this->assertEquals(1, $this->getConnection()->getRowCount(Propel\Map\PermissionTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testDeletePermissionWithEmptyId()
    {
        // Act
        $this->access_control->deletePermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     */
    public function testDeletePermissionWithInvalidId()
    {
        // Act
        $result = $this->access_control->deletePermission('TEST_PERMISSION');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testDeletePermissionException()
    {
        // Act
        $this->access_control_faulty->deletePermission('TEST_PERMISSION');
    }

    /**
     * @depends testCreatePermission
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission
     * @uses \Phlopsi\AccessControl\AccessControl::createPermission
     */
    public function testDeletePermission()
    {
        // Arrange
        $this->access_control->createPermission('TEST_PERMISSION');

        // Act
        $result = $this->access_control->deletePermission('TEST_PERMISSION');

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount(Propel\Map\PermissionTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testCreateRoleWithEmptyId()
    {
        // Act
        $this->access_control->createRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreateRoleException()
    {
        // Act
        $this->access_control_faulty->createRole('TEST_ROLE');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole
     */
    public function testCreateRole()
    {
        // Act
        $role = $this->access_control->createRole('TEST_ROLE');

        // Assert
        $this->assertInstanceOf(\Phlopsi\AccessControl\Role::class, $role);
        $this->assertEquals(1, $this->getConnection()->getRowCount(Propel\Map\RoleTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testDeleteRoleWithEmptyId()
    {
        // Act
        $this->access_control->deleteRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     */
    public function testDeleteRoleWithInvalidId()
    {
        // Act
        $result = $this->access_control->deleteRole('TEST_ROLE');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testDeleteRoleException()
    {
        // Act
        $this->access_control_faulty->deleteRole('TEST_ROLE');
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole
     * @uses \Phlopsi\AccessControl\AccessControl::createRole
     */
    public function testDeleteRole()
    {
        // Arrange
        $this->access_control->createRole('TEST_ROLE');

        // Act
        $result = $this->access_control->deleteRole('TEST_ROLE');

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount(Propel\Map\RoleTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRetrieveRoleWithEmptyId()
    {
        // Act
        $this->access_control->retrieveRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveRoleWithInvalidId()
    {
        // Act
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
        // Arrange
        try {
            $this->access_control->createRole('TEST_ROLE');
        } catch (\Exception $exception) {
            $this->fail($exception->getTraceAsString());
        }

        // Act
        $this->access_control_faulty->retrieveRole('TEST_ROLE');
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole
     * @uses \Phlopsi\AccessControl\AccessControl::createRole
     */
    public function testRetrieveRole()
    {
        // Arrange
        $this->access_control->createRole('TEST_ROLE');

        // Act
        $role = $this->access_control->retrieveRole('TEST_ROLE');

        // Assert
        $this->assertInstanceOf(\Phlopsi\AccessControl\Role::class, $role);
        $this->assertEquals('TEST_ROLE', $role->getId());
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testCreateSessionTypeWithEmptyId()
    {
        // Act
        $this->access_control->createSessionType('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreateSessionTypeException()
    {
        // Act
        $this->access_control_faulty->createSessionType('TEST_SESSION_TYPE');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createSessionType
     */
    public function testCreateSessionType()
    {
        // Act
        $session_type = $this->access_control->createSessionType('TEST_SESSION_TYPE');

        // Assert
        $this->assertInstanceOf(\Phlopsi\AccessControl\SessionType::class, $session_type);
        $this->assertEquals(1, $this->getConnection()->getRowCount(Propel\Map\SessionTypeTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testDeleteSessionTypeWithEmptyId()
    {
        // Act
        $this->access_control->deleteSessionType('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     */
    public function testDeleteSessionTypeWithInvalidId()
    {
        // Act
        $result = $this->access_control->deleteSessionType('TEST_SESSION_TYPE');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testDeleteSessionTypeException()
    {
        // Act
        $this->access_control_faulty->deleteSessionType('TEST_SESSION_TYPE');
    }

    /**
     * @depends testCreateSessionType
     * @covers \Phlopsi\AccessControl\AccessControl::deleteSessionType
     * @uses \Phlopsi\AccessControl\AccessControl::createSessionType
     */
    public function testDeleteSessionType()
    {
        // Arrange
        $this->access_control->createSessionType('TEST_SESSION_TYPE');

        // Act
        $result = $this->access_control->deleteSessionType('TEST_SESSION_TYPE');

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount(Propel\Map\SessionTypeTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRetrieveSessionTypeWithEmptyId()
    {
        // Act
        $this->access_control->retrieveSessionType('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveSessionType
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveSessionTypeWithInvalidId()
    {
        // Act
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
        // Arrange
        try {
            $this->access_control->createSessionType('TEST_SESSION_TYPE');
        } catch (\Exception $exception) {
            $this->fail($exception->getTraceAsString());
        }

        // Act
        $this->access_control_faulty->retrieveSessionType('TEST_SESSION_TYPE');
    }

    /**
     * @depends testCreateSessionType
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveSessionType
     * @uses \Phlopsi\AccessControl\AccessControl::createSessionType
     */
    public function testRetrieveSessionType()
    {
        // Arrange
        $this->access_control->createSessionType('TEST_SESSION_TYPE');

        // Act
        $sesssion_type = $this->access_control->retrieveSessionType('TEST_SESSION_TYPE');

        // Assert
        $this->assertInstanceOf(\Phlopsi\AccessControl\SessionType::class, $sesssion_type);
        $this->assertEquals('TEST_SESSION_TYPE', $sesssion_type->getId());
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testCreateUserWithEmptyId()
    {
        // Act
        $this->access_control->createUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testCreateUserException()
    {
        // Act
        $this->access_control_faulty->createUser('TEST_USER');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser
     */
    public function testCreateUser()
    {
        // Act
        $user = $this->access_control->createUser('TEST_USER');

        // Assert
        $this->assertInstanceOf(\Phlopsi\AccessControl\User::class, $user);
        $this->assertEquals(1, $this->getConnection()->getRowCount(Propel\Map\UserTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testDeleteUserWithEmptyId()
    {
        // Act
        $this->access_control->deleteUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     */
    public function testDeleteUserWithInvalidId()
    {
        // Act
        $result = $this->access_control->deleteUser('TEST_USER');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testDeleteUserException()
    {
        // Act
        $this->access_control_faulty->deleteUser('TEST_USER');
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser
     * @uses \Phlopsi\AccessControl\AccessControl::createUser
     */
    public function testDeleteUser()
    {
        // Arrange
        $this->access_control->createUser('TEST_USER');

        // Act
        $result = $this->access_control->deleteUser('TEST_USER');

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(0, $this->getConnection()->getRowCount(Propel\Map\UserTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRetrieveUserWithEmptyId()
    {
        // Act
        $this->access_control->retrieveUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRetrieveUserWithInvalidId()
    {
        // Act
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
        // Arrange
        try {
            $this->access_control->createUser('TEST_USER');
        } catch (\Exception $exception) {
            $this->fail($exception->getTraceAsString());
        }

        // Act
        $this->access_control_faulty->retrieveUser('TEST_USER');
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser
     * @uses \Phlopsi\AccessControl\AccessControl::createUser
     */
    public function testRetrieveUser()
    {
        // Arrange
        $this->access_control->createUser('TEST_USER');

        // Act
        $user = $this->access_control->retrieveUser('TEST_USER');

        // Assert
        $this->assertInstanceOf(\Phlopsi\AccessControl\User::class, $user);
        $this->assertEquals('TEST_USER', $user->getId());
    }
}
