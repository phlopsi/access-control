<?php
namespace Phlopsi\AccessControl;

use Propel\Generator\Util\SqlParser;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class RoleTest extends \PHPUnit_Extensions_Database_TestCase
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
        $role = new Role($propel_role, $this->getFaultyConnection());

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
            ->setExternalId('TEST_ROLE')
            ->save();

        $role = new Role($propel_role);

        // Act
        $role->addPermission('TEST_ROLE');

        // Assert
        $this->assertEquals(1, $this->getConnection()->getRowCount(Propel\Map\PermissionToRoleTableMap::TABLE_NAME));
    }
}
