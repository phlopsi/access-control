<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Propel\Generator\Util\SqlParser;

class RoleTest extends \PHPUnit_Extensions_Database_TestCase
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
     * @covers \Phlopsi\AccessControl\Role::addPermission
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
     * @covers \Phlopsi\AccessControl\Role::addPermission
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
     * @covers \Phlopsi\AccessControl\Role::addPermission
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
     * @covers \Phlopsi\AccessControl\Role::addPermission
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
        $this->assertEquals(1, $this->getConnection()->getRowCount(Propel\Map\PermissionToRoleTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\Role::removePermission
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
     * @covers \Phlopsi\AccessControl\Role::removePermission
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
     * @covers \Phlopsi\AccessControl\Role::removePermission
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
     * @covers \Phlopsi\AccessControl\Role::removePermission
     * @uses \Phlopsi\AccessControl\Role::addPermission
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
        $this->assertEquals(0, $this->getConnection()->getRowCount(Propel\Map\PermissionToRoleTableMap::TABLE_NAME));
    }
}
