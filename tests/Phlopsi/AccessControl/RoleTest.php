<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\Map\PermissionToRoleTableMap;
use Phlopsi\AccessControl\Propel\Map\RoleToUserTableMap;
use Phlopsi\AccessControl\Propel\Permission as PropelPermission;
use Phlopsi\AccessControl\Propel\Role as PropelRole;
use Phlopsi\AccessControl\Propel\User as PropelUser;
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
    public function testAddPermissionWithEmptyId()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $role->addPermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddPermissionWithInvalidId()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $role->addPermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddPermissionException()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);
        $role->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $role->addPermission('TEST_ROLE');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddPermission()
    {
        // Arrange
        $propel_role = new PropelRole();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $role = new Role($propel_role);

        (new PropelPermission())
            ->setExternalId('TEST_PERMISSION')
            ->save();

        // Act
        $role->addPermission('TEST_PERMISSION');

        // Assert
        $message = 'Assert that "%s"."%s" has 1 row';
        $message = sprintf($message, PermissionToRoleTableMap::DATABASE_NAME, PermissionToRoleTableMap::TABLE_NAME);
        $this->assertTableRowCount(PermissionToRoleTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddPermissionTwice()
    {
        // Arrange
        $propel_role = new PropelRole();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $role = new Role($propel_role);

        (new PropelPermission())
            ->setExternalId('TEST_PERMISSION')
            ->save();

        $role->addPermission('TEST_PERMISSION');

        // Act
        $role->addPermission('TEST_PERMISSION');

        // Assert
        $message = 'Assert that "%s"."%s" has 1 row';
        $message = sprintf($message, PermissionToRoleTableMap::DATABASE_NAME, PermissionToRoleTableMap::TABLE_NAME);
        $this->assertTableRowCount(PermissionToRoleTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemovePermissionWithEmptyId()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $role->removePermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemovePermissionWithInvalidId()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $role->removePermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemovePermissionException()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);
        $role->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $role->removePermission('TEST_PERMISSION');
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
        $propel_role = new PropelRole();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        (new PropelPermission())
            ->setExternalId('TEST_PERMISSION')
            ->save();

        $role = new Role($propel_role);
        $role->addPermission('TEST_PERMISSION');

        // Act
        $role->removePermission('TEST_PERMISSION');

        // Assert
        $message = 'Assert that "%s"."%s" is empty';
        $message = sprintf($message, PermissionToRoleTableMap::DATABASE_NAME, PermissionToRoleTableMap::TABLE_NAME);
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
        $propel_role = new PropelRole();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $role = new Role($propel_role);

        (new PropelPermission())
            ->setExternalId('TEST_PERMISSION')
            ->save();

        // Act
        $role->removePermission('TEST_PERMISSION');

        // Assert
        $this->addToAssertionCount(1);
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddUserWithEmptyId()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $role->addUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddUserWithInvalidId()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $role->addUser('TEST_USER');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddUserException()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);
        $role->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $role->addUser('TEST_USER');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testAddUser()
    {
        // Arrange
        $propel_role = new PropelRole();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $role = new Role($propel_role);

        (new PropelUser())
            ->setExternalId('TEST_USER')
            ->save();

        // Act
        $role->addUser('TEST_USER');

        // Assert
        $message = 'Assert that "%s"."%s" has 1 row';
        $message = sprintf($message, RoleToUserTableMap::DATABASE_NAME, RoleToUserTableMap::TABLE_NAME);
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
        $propel_role = new PropelRole();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $role = new Role($propel_role);

        (new PropelUser())
            ->setExternalId('TEST_USER')
            ->save();

        $role->addUser('TEST_USER');

        // Act
        $role->addUser('TEST_USER');

        // Assert
        $message = 'Assert that "%s"."%s" has 1 row';
        $message = sprintf($message, RoleToUserTableMap::DATABASE_NAME, RoleToUserTableMap::TABLE_NAME);
        $this->assertTableRowCount(RoleToUserTableMap::TABLE_NAME, 1, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemoveUserWithEmptyId()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $role->removeUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemoveUserWithInvalidId()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $role->removeUser('TEST_USER');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRemoveUserException()
    {
        // Arrange
        $propel_role = $this->getMock(PropelRole::class);
        $role = new Role($propel_role);
        $role->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $role->removeUser('TEST_USER');
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
        $propel_role = new PropelRole();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        (new PropelUser())
            ->setExternalId('TEST_USER')
            ->save();

        $role = new Role($propel_role);
        $role->addUser('TEST_USER');

        // Act
        $role->removeUser('TEST_USER');

        // Assert
        $message = 'Assert that "%s"."%s" is empty';
        $message = sprintf($message, RoleToUserTableMap::DATABASE_NAME, RoleToUserTableMap::TABLE_NAME);
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
        $propel_role = new PropelRole();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $role = new Role($propel_role);

        (new PropelUser())
            ->setExternalId('TEST_USER')
            ->save();

        // Act
        $role->removeUser('TEST_USER');

        // Assert
        $this->addToAssertionCount(1);
    }
}
