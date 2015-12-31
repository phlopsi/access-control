<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

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
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testAddPermissionWithEmptyId()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);

        // Act
        $role->addPermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testAddPermissionWithInvalidId()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);

        // Act
        $role->addPermission('TEST_ROLE');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testAddPermissionException()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);
        $role->setConnection($this->getFaultyConnection());

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
        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $propel_permission = new Propel\Permission();
        $propel_permission
            ->setExternalId('TEST_PERMISSION')
            ->save();

        $role = new Role($propel_role);

        // Act
        $role->addPermission('TEST_PERMISSION');

        // Assert
        $this->assertEquals(
            1,
            $this->getConnection()->getRowCount(Propel\Map\PermissionToRoleTableMap::TABLE_NAME),
            'Assert that "access_control"."permissions_roles" has 1 row'
        );
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRemovePermissionWithEmptyId()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);

        // Act
        $role->removePermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRemovePermissionWithInvalidId()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);

        // Act
        $role->removePermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRemovePermissionException()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);
        $role->setConnection($this->getFaultyConnection());

        // Act
        $role->removePermission('TEST_PERMISSION');
    }

    /**
     * @depends testAddPermission
     * @covers \Phlopsi\AccessControl\Role::removePermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @uses \Phlopsi\AccessControl\Role::addPermission()
     */
    public function testRemovePermission()
    {
        // Arrange
        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $propel_permission = new Propel\Permission();
        $propel_permission
            ->setExternalId('TEST_PERMISSION')
            ->save();

        $role = new Role($propel_role);
        $role->addPermission('TEST_PERMISSION');

        // Act
        $role->removePermission('TEST_PERMISSION');

        // Assert
        $this->assertEquals(
            0,
            $this->getConnection()->getRowCount(Propel\Map\PermissionToRoleTableMap::TABLE_NAME),
            'Assert that "access_control"."permissions_roles" has no rows'
        );
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testAddUserWithEmptyId()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);

        // Act
        $role->addUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testAddUserWithInvalidId()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);

        // Act
        $role->addUser('TEST_USER');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::addUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testAddUserException()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);
        $role->setConnection($this->getFaultyConnection());

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
        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $propel_user = new Propel\User();
        $propel_user
            ->setExternalId('TEST_USER')
            ->save();

        $role = new Role($propel_role);

        // Act
        $role->addUser('TEST_USER');

        // Assert
        $this->assertEquals(
            1,
            $this->getConnection()->getRowCount(Propel\Map\RoleToUserTableMap::TABLE_NAME),
            'Assert that "access_control"."roles_users" has 1 row'
        );
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRemoveUserWithEmptyId()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);

        // Act
        $role->removeUser('');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRemoveUserWithInvalidId()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);

        // Act
        $role->removeUser('TEST_USER');
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRemoveUserException()
    {
        // Arrange
        $propel_role = $this->getMock(Propel\Role::class);
        $role = new Role($propel_role);
        $role->setConnection($this->getFaultyConnection());

        // Act
        $role->removeUser('TEST_USER');
    }

    /**
     * @depends testAddUser
     * @covers \Phlopsi\AccessControl\Role::removeUser()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     * @uses \Phlopsi\AccessControl\Role::addUser()
     */
    public function testRemoveUser()
    {
        // Arrange
        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $propel_user = new Propel\User();
        $propel_user
            ->setExternalId('TEST_USER')
            ->save();

        $role = new Role($propel_role);
        $role->addUser('TEST_USER');

        // Act
        $role->removeUser('TEST_USER');

        // Assert
        $this->assertEquals(
            0,
            $this->getConnection()->getRowCount(Propel\Map\RoleToUserTableMap::TABLE_NAME),
            'Assert that "access_control"."roles_users" has no rows'
        );
    }
}
