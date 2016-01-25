<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Propel\Map\PermissionToRoleTableMap;
use Phlopsi\AccessControl\Propel\Map\RoleToUserTableMap;
use Propel\Generator\Util\SqlParser;

class RoleTest extends \PHPUnit_Extensions_Database_TestCase
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
     * @covers \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddPermission()
    {
        // Arrange
        $access_control = new AccessControl();

        $role = $access_control->createRole('TEST_ROLE');
        $permission = $access_control->createPermission('TEST_PERMISSION');

        // Act
        $role->addPermission($permission);

        // Assert
        $message = 'Assert that "%s"."%s" has 1 row';
        $message = \sprintf($message, PermissionToRoleTableMap::DATABASE_NAME, PermissionToRoleTableMap::TABLE_NAME);
        $this->assertTableRowCount(PermissionToRoleTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @depends testAddPermission
     * @covers \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddPermissionTwice()
    {
        // Arrange
        $access_control = new AccessControl();

        $role = $access_control->createRole('TEST_ROLE');
        $permission = $access_control->createPermission('TEST_PERMISSION');

        $role->addPermission($permission);

        // Act
        $role->addPermission($permission);

        // Assert
        $message = 'Assert that "%s"."%s" has 1 row';
        $message = \sprintf($message, PermissionToRoleTableMap::DATABASE_NAME, PermissionToRoleTableMap::TABLE_NAME);
        $this->assertTableRowCount(PermissionToRoleTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @depends testAddPermission
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemovePermission()
    {
        // Arrange
        $access_control = new AccessControl();

        $role = $access_control->createRole('TEST_ROLE');
        $permission = $access_control->createPermission('TEST_PERMISSION');

        $role->addPermission($permission);

        // Act
        $role->removePermission($permission);

        // Assert
        $message = 'Assert that "%s"."%s" is empty';
        $message = \sprintf($message, PermissionToRoleTableMap::DATABASE_NAME, PermissionToRoleTableMap::TABLE_NAME);
        $this->assertTableRowCount(PermissionToRoleTableMap::TABLE_NAME, 0, $message);
    }

    /**
     * @depends testRemovePermission
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemovePermissionWithoutRelation()
    {
        // Arrange
        $access_control = new AccessControl();

        $role = $access_control->createRole('TEST_ROLE');
        $permission = $access_control->createPermission('TEST_PERMISSION');

        // Act
        $role->removePermission($permission);

        // Assert
        $this->addToAssertionCount(1);
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddUser()
    {
        // Arrange
        $access_control = new AccessControl();

        $role = $access_control->createRole('TEST_ROLE');
        $user = $access_control->createUser('TEST_USER');

        // Act
        $role->addUser($user);

        // Assert
        $message = 'Assert that "%s"."%s" has 1 row';
        $message = \sprintf($message, RoleToUserTableMap::DATABASE_NAME, RoleToUserTableMap::TABLE_NAME);
        $this->assertTableRowCount(RoleToUserTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @depends testAddUser
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddUserTwice()
    {
        // Arrange
        $access_control = new AccessControl();

        $role = $access_control->createRole('TEST_ROLE');
        $user = $access_control->createUser('TEST_USER');

        $role->addUser($user);

        // Act
        $role->addUser($user);

        // Assert
        $message = 'Assert that "%s"."%s" has 1 row';
        $message = \sprintf($message, RoleToUserTableMap::DATABASE_NAME, RoleToUserTableMap::TABLE_NAME);
        $this->assertTableRowCount(RoleToUserTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @depends testAddUser
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemoveUser()
    {
        // Arrange
        $access_control = new AccessControl();

        $role = $access_control->createRole('TEST_ROLE');
        $user = $access_control->createUser('TEST_USER');

        $role->addUser($user);

        // Act
        $role->removeUser($user);

        // Assert
        $message = 'Assert that "%s"."%s" is empty';
        $message = \sprintf($message, RoleToUserTableMap::DATABASE_NAME, RoleToUserTableMap::TABLE_NAME);
        $this->assertTableRowCount(RoleToUserTableMap::TABLE_NAME, 0, $message);
    }

    /**
     * @depends testRemoveUser
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemoveUserWithoutRelation()
    {
        // Arrange
        $access_control = new AccessControl();

        $role = $access_control->createRole('TEST_ROLE');
        $user = $access_control->createUser('TEST_USER');

        // Act
        $role->removeUser($user);

        // Assert
        $this->addToAssertionCount(1);
    }
}
