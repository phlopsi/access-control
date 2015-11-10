<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl;

use Propel\Generator\Util\SqlParser;

class UserTest extends \PHPUnit_Extensions_Database_TestCase
{
    use Test\DatabaseTestCaseTrait;

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
     * @covers \Phlopsi\AccessControl\User::addRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testAddRoleWithEmptyId()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);

        // Act
        $user->addRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::addRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testAddRoleWithInvalidId()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);

        // Act
        $user->addRole('TEST_ROLE');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::addRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testAddRoleException()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);
        $user->setConnection($this->getFaultyConnection());

        // Act
        $user->addRole('TEST_ROLE');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::addRole
     */
    public function testAddRole()
    {
        // Arrange
        $propel_user = new Propel\User();
        $propel_user
            ->setExternalId('TEST_USER')
            ->save();

        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $user = new User($propel_user);

        // Act
        $user->addRole('TEST_ROLE');

        // Assert
        $this->assertEquals(1, $this->getConnection()->getRowCount(Propel\Map\RoleToUserTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\User::removeRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRemoveRoleWithEmptyId()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);

        // Act
        $user->removeRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::removeRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRemoveRoleWithInvalidId()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);

        // Act
        $user->removeRole('TEST_ROLE');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::removeRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRemoveRoleException()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);
        $user->setConnection($this->getFaultyConnection());

        // Act
        $user->removeRole('TEST_ROLE');
    }

    /**
     * @depends testAddRole
     * @covers \Phlopsi\AccessControl\User::removeRole
     * @uses \Phlopsi\AccessControl\User::addRole
     */
    public function testRemoveRole()
    {
        // Arrange
        $propel_user = new Propel\User();
        $propel_user
            ->setExternalId('TEST_USER')
            ->save();

        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $user = new User($propel_user);
        $user->addRole('TEST_ROLE');

        // Act
        $user->removeRole('TEST_ROLE');

        // Assert
        $this->assertEquals(0, $this->getConnection()->getRowCount(Propel\Map\RoleToUserTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testHasPermissionWithEmptyId()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);

        // Act
        $user->hasPermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testHasPermissionWithInvalidId()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);

        // Act
        $user->hasPermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testHasPermissionException()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);
        $user->setConnection($this->getFaultyConnection());

        // Act
        $user->hasPermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission
     */
    public function testHasPermissionFalse()
    {
        // Arrange
        $propel_user = new Propel\User();
        $propel_user
            ->setExternalId('TEST_USER')
            ->save();

        $propel_permission = new Propel\Permission();
        $propel_permission
            ->setExternalId('TEST_PERMISSION')
            ->save();

        $user = new User($propel_user);

        // Act
        $has_permission = $user->hasPermission('TEST_PERMISSION');

        // Assert
        $this->assertFalse($has_permission);
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission
     */
    public function testHasPermissionTrue()
    {
        // Arrange
        $propel_permission = new Propel\Permission();
        $propel_permission
            ->setExternalId('TEST_PERMISSION')
            ->save();

        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->addPermission($propel_permission)
            ->save();

        $propel_user = new Propel\User();
        $propel_user
            ->setExternalId('TEST_USER')
            ->addRole($propel_role)
            ->save();

        $user = new User($propel_user);

        // Act
        $has_permission = $user->hasPermission('TEST_PERMISSION');

        // Assert
        $this->assertTrue($has_permission);
    }
}
