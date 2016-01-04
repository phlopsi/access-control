<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\Map\PermissionTableMap;
use Phlopsi\AccessControl\Propel\Map\RoleTableMap;
use Phlopsi\AccessControl\Propel\Map\UserTableMap;
use Propel\Generator\Util\SqlParser;

class AccessControlTest extends \PHPUnit_Extensions_Database_TestCase
{
    use \Phlopsi\PHPUnit\DatabaseTestCaseTrait;

    /**
     * @var \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    private $connection;

    protected function setUp()
    {
        $this->connection = $this->createDefaultDBConnection(self::$pdo);

        SqlParser::executeString(self::$sql, self::$pdo);
    }

    protected function tearDown()
    {
        $this->connection = null;
    }

    protected function getConnection()
    {
        return $this->connection;
    }

    protected function getDataSet()
    {
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreatePermissionWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->createPermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreatePermissionException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->createPermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreatePermission()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->createPermission('TEST_PERMISSION');

        // Assert
        $message = 'Assert that %s::createPermission() returns an object of type %s';
        $message = \sprintf($message, AccessControl::class, Permission::class);
        $this->assertTrue(\is_object($result), $message);
        $this->assertInstanceOf(Permission::class, $result, $message);

        $message = 'Assert that "%s"."%s" has 1 row';
        $message = \sprintf($message, PermissionTableMap::DATABASE_NAME, PermissionTableMap::TABLE_NAME);
        $this->assertTableRowCount(PermissionTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeletePermissionException()
    {
        // Arrange
        $access_control = new AccessControl();
        $permission = $access_control->createPermission('TEST_PERMISSION');

        $access_control_faulty = clone $access_control;
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->deletePermission($permission);
    }

    /**
     * @depends testCreatePermission
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission()
     * @uses \Phlopsi\AccessControl\AccessControl::createPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeletePermission()
    {
        // Arrange
        $access_control = new AccessControl();
        $permission = $access_control->createPermission('TEST_PERMISSION');

        // Act
        $access_control->deletePermission($permission);

        // Assert
        $message = 'Assert that "%s"."%s" is empty';
        $message = \sprintf($message, PermissionTableMap::DATABASE_NAME, PermissionTableMap::TABLE_NAME);
        $this->assertTableRowCount(PermissionTableMap::TABLE_NAME, 0, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrievePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrievePermissionWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->retrievePermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrievePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrievePermissionWithInvalidId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control->retrievePermission('TEST_PERMISSION');
    }

    /**
     * @depends testCreatePermission
     * @covers \Phlopsi\AccessControl\AccessControl::retrievePermission()
     * @uses \Phlopsi\AccessControl\AccessControl::createPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrievePermissionException()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createPermission('TEST_PERMISSION');

        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->retrievePermission('TEST_PERMISSION');
    }

    /**
     * @depends testCreatePermission
     * @covers \Phlopsi\AccessControl\AccessControl::retrievePermission()
     * @uses \Phlopsi\AccessControl\AccessControl::createPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrievePermission()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createPermission('TEST_PERMISSION');

        // Act
        $permission = $access_control->retrievePermission('TEST_PERMISSION');
        $result = $permission->getId();

        // Assert
        $message = 'Assert that %s::retrievePermission() returns the correct permission';
        $message = \sprintf($message, AccessControl::class);
        $this->assertEquals('TEST_PERMISSION', $result, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrievePermissionList()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrievePermissionListException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->retrievePermissionList();
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrievePermissionList()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveEmptyPermissionList()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->retrievePermissionList();

        // Assert
        $message = \sprintf('Assert that %s::retrievePermissionList() returns an empty array', AccessControl::class);
        $this->assertEmpty($result, $message);
    }

    /**
     * @depends testCreatePermission
     * @covers \Phlopsi\AccessControl\AccessControl::retrievePermissionList()
     * @uses \Phlopsi\AccessControl\AccessControl::createPermission
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrievePermissionList()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createPermission('TEST_PERMISSION_0');
        $access_control->createPermission('TEST_PERMISSION_1');
        $access_control->createPermission('TEST_PERMISSION_2');

        // Act
        $result = $access_control->retrievePermissionList();

        // Assert
        $message = 'Assert that %s::retrievePermissionList() returns an array that contains 3 permissions';
        $message = \sprintf($message, AccessControl::class);
        $this->assertCount(3, $result, $message);
        $this->assertContains('TEST_PERMISSION_0', $result, $message);
        $this->assertContains('TEST_PERMISSION_1', $result, $message);
        $this->assertContains('TEST_PERMISSION_2', $result, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreateRoleWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->createRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreateRoleException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->createRole('TEST_ROLE');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreateRole()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->createRole('TEST_ROLE');

        // Assert
        $message = 'Assert that %s::createRole() returns an object of type %s';
        $message = \sprintf($message, AccessControl::class, Role::class);
        $this->assertTrue(\is_object($result), $message);
        $this->assertInstanceOf(Role::class, $result, $message);

        $message = 'Assert that "%s"."%s" has 1 row';
        $message = \sprintf($message, RoleTableMap::DATABASE_NAME, RoleTableMap::TABLE_NAME);
        $this->assertTableRowCount(RoleTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteRoleException()
    {
        // Arrange
        $access_control = new AccessControl();
        $role = $access_control->createRole('TEST_ROLE');

        $access_control_faulty = clone $access_control;
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->deleteRole($role);
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole()
     * @uses \Phlopsi\AccessControl\AccessControl::createRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteRole()
    {
        // Arrange
        $access_control = new AccessControl();
        $role = $access_control->createRole('TEST_ROLE');

        // Act
        $access_control->deleteRole($role);

        // Assert
        $message = 'Assert that "%s"."%s" is empty';
        $message = \sprintf($message, RoleTableMap::DATABASE_NAME, RoleTableMap::TABLE_NAME);
        $this->assertTableRowCount(RoleTableMap::TABLE_NAME, 0, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveRoleWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->retrieveRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveRoleWithInvalidId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control->retrieveRole('TEST_ROLE');
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole()
     * @uses \Phlopsi\AccessControl\AccessControl::createRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveRoleException()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createRole('TEST_ROLE');

        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->retrieveRole('TEST_ROLE');
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRole()
     * @uses \Phlopsi\AccessControl\AccessControl::createRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveRole()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createRole('TEST_ROLE');

        // Act
        $role = $access_control->retrieveRole('TEST_ROLE');
        $result = $role->getId();

        // Assert
        $message = 'Assert that %s::retrieveRole() returns the correct role';
        $message = \sprintf($message, AccessControl::class);
        $this->assertEquals('TEST_ROLE', $result, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRoleList()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveRoleListException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->retrieveRoleList();
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRoleList()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveEmptyRoleList()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->retrieveRoleList();

        // Assert
        $message = \sprintf('Assert that %s::retrieveRoleList() returns an empty array', AccessControl::class);
        $this->assertEmpty($result, $message);
    }

    /**
     * @depends testCreateRole
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveRoleList()
     * @uses \Phlopsi\AccessControl\AccessControl::createRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveRoleList()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createRole('TEST_ROLE_0');
        $access_control->createRole('TEST_ROLE_1');
        $access_control->createRole('TEST_ROLE_2');

        // Act
        $result = $access_control->retrieveRoleList();

        // Assert
        $message = 'Assert that %s::retrieveRoleList() returns an array that contains 3 roles';
        $message = \sprintf($message, AccessControl::class);
        $this->assertCount(3, $result, $message);
        $this->assertContains('TEST_ROLE_0', $result, $message);
        $this->assertContains('TEST_ROLE_1', $result, $message);
        $this->assertContains('TEST_ROLE_2', $result, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreateUserWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->createUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreateUserException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->createUser('TEST_USER');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::createUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreateUser()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->createUser('TEST_USER');

        // Assert
        $message = 'Assert that %s::createUser() returns an object of type %s';
        $message = \sprintf($message, AccessControl::class, User::class);
        $this->assertTrue(\is_object($result), $message);
        $this->assertInstanceOf(User::class, $result, $message);

        $message = 'Assert that "%s"."%s" has 1 row';
        $message = \sprintf($message, UserTableMap::DATABASE_NAME, UserTableMap::TABLE_NAME);
        $this->assertTableRowCount(UserTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteUserException()
    {
        // Arrange
        $access_control = new AccessControl();
        $user = $access_control->createUser('TEST_USER');

        $access_control_faulty = clone $access_control;
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->deleteUser($user);
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser()
     * @uses \Phlopsi\AccessControl\AccessControl::createUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteUser()
    {
        // Arrange
        $access_control = new AccessControl();
        $user = $access_control->createUser('TEST_USER');

        // Act
        $access_control->deleteUser($user);

        // Assert
        $message = 'Assert that "%s"."%s" is empty';
        $message = \sprintf($message, UserTableMap::DATABASE_NAME, UserTableMap::TABLE_NAME);
        $this->assertTableRowCount(UserTableMap::TABLE_NAME, 0, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveUserWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->retrieveUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveUserWithInvalidId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control->retrieveUser('TEST_USER');
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser()
     * @uses \Phlopsi\AccessControl\AccessControl::createUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveUserException()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createUser('TEST_USER');

        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->retrieveUser('TEST_USER');
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUser()
     * @uses \Phlopsi\AccessControl\AccessControl::createUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveUser()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createUser('TEST_USER');

        // Act
        $user = $access_control->retrieveUser('TEST_USER');
        $result = $user->getId();

        // Assert
        $message = 'Assert that %s::retrieveUser() returns the correct user';
        $message = \sprintf($message, AccessControl::class);
        $this->assertEquals('TEST_USER', $result, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUserList()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveUserListException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->retrieveUserList();
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUserList()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveEmptyUserList()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->retrieveUserList();

        // Assert
        $message = \sprintf('Assert that %s::retrieveUserList() returns an empty array', AccessControl::class);
        $this->assertEmpty($result, $message);
    }

    /**
     * @depends testCreateUser
     * @covers \Phlopsi\AccessControl\AccessControl::retrieveUserList()
     * @uses \Phlopsi\AccessControl\AccessControl::createUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrieveUserList()
    {
        // Arrange
        $access_control = new AccessControl();
        $access_control->createUser('TEST_USER_0');
        $access_control->createUser('TEST_USER_1');
        $access_control->createUser('TEST_USER_2');

        // Act
        $result = $access_control->retrieveUserList();

        // Assert
        $message = 'Assert that %s::retrieveUserList() returns an array that contains 3 users';
        $message = \sprintf($message, AccessControl::class);
        $this->assertCount(3, $result, $message);
        $this->assertContains('TEST_USER_0', $result, $message);
        $this->assertContains('TEST_USER_1', $result, $message);
        $this->assertContains('TEST_USER_2', $result, $message);
    }
}
