<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
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
        $access_control->createPermission('TEST_PERMISSION');

        // Assert
        $this->assertEquals(
            1,
            $this->getConnection()->getRowCount(Propel\Map\PermissionTableMap::TABLE_NAME),
            'Assert that "access_control"."permissions" has 1 row'
        );
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeletePermissionWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->deletePermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeletePermissionWithInvalidId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->deletePermission('TEST_PERMISSION');

        // Assert
        $this->assertFalse($result, 'Assert that AccessControl::deletePermission returns false');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deletePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeletePermissionException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->deletePermission('TEST_PERMISSION');
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
        $access_control->createPermission('TEST_PERMISSION');

        // Act
        $result = $access_control->deletePermission('TEST_PERMISSION');

        // Assert
        $this->assertTrue($result, 'Assert that AccessControl::deletePermission returns true');

        $this->assertEquals(
            0,
            $this->getConnection()->getRowCount(Propel\Map\PermissionTableMap::TABLE_NAME),
            'Assert that "access_control"."permissions" has no rows'
        );
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
        $this->assertEmpty($result);
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
        $this->assertCount(3, $result);
        $this->assertContains('TEST_PERMISSION_0', $result);
        $this->assertContains('TEST_PERMISSION_1', $result);
        $this->assertContains('TEST_PERMISSION_2', $result);
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
        $role = $access_control->createRole('TEST_ROLE');

        // Assert
        $this->assertEquals(
            1,
            $this->getConnection()->getRowCount(Propel\Map\RoleTableMap::TABLE_NAME),
            'Assert that "access_control"."roles" has 1 row'
        );
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteRoleWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->deleteRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteRoleWithInvalidId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->deleteRole('TEST_ROLE');

        // Assert
        $this->assertFalse($result, 'Assert that AccessControl::deleteRole returns false');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteRole()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteRoleException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->deleteRole('TEST_ROLE');
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
        $access_control->createRole('TEST_ROLE');

        // Act
        $result = $access_control->deleteRole('TEST_ROLE');

        // Assert
        $this->assertTrue($result, 'Assert that AccessControl::deleteRole returns true');

        $this->assertEquals(
            0,
            $this->getConnection()->getRowCount(Propel\Map\RoleTableMap::TABLE_NAME),
            'Assert that "access_control"."roles" has no rows'
        );
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

        // Assert
        $this->assertEquals('TEST_ROLE', $role->getId());
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
        $this->assertEmpty($result);
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
        $this->assertCount(3, $result);
        $this->assertContains('TEST_ROLE_0', $result);
        $this->assertContains('TEST_ROLE_1', $result);
        $this->assertContains('TEST_ROLE_2', $result);
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
        $user = $access_control->createUser('TEST_USER');

        // Assert
        $this->assertEquals(
            1,
            $this->getConnection()->getRowCount(Propel\Map\UserTableMap::TABLE_NAME),
            'Assert that "access_control"."users" has 1 row'
        );
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteUserWithEmptyId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $access_control->deleteUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteUserWithInvalidId()
    {
        // Arrange
        $access_control = new AccessControl();

        // Act
        $result = $access_control->deleteUser('TEST_USER');

        // Assert
        $this->assertFalse($result, 'Assert that AccessControl::deleteUser returns false');
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::deleteUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeleteUserException()
    {
        // Arrange
        $access_control_faulty = new AccessControl();
        $access_control_faulty->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $access_control_faulty->deleteUser('TEST_USER');
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
        $access_control->createUser('TEST_USER');

        // Act
        $result = $access_control->deleteUser('TEST_USER');

        // Assert
        $this->assertTrue($result, 'Assert that AccessControl::deleteUser returns true');

        $this->assertEquals(
            0,
            $this->getConnection()->getRowCount(Propel\Map\UserTableMap::TABLE_NAME),
            'Assert that "access_control"."users" has no rows'
        );
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

        // Assert
        $this->assertEquals('TEST_USER', $user->getId());
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
        $this->assertEmpty($result);
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
        $this->assertCount(3, $result);
        $this->assertContains('TEST_USER_0', $result);
        $this->assertContains('TEST_USER_1', $result);
        $this->assertContains('TEST_USER_2', $result);
    }
}
