<?php
namespace Phlopsi\AccessControl;

use Propel\Generator\Util\SqlParser;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class SessionTypeTest extends \PHPUnit_Extensions_Database_TestCase
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
     * @covers \Phlopsi\AccessControl\SessionType::addRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testAddRoleWithEmptyId()
    {
        // Arrange
        $propel_session_type = $this->getMock(Propel\SessionType::class);
        $session_type = new SessionType($propel_session_type);

        // Act
        $session_type->addRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\SessionType::addRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testAddRoleWithInvalidId()
    {
        // Arrange
        $propel_session_type = $this->getMock(Propel\SessionType::class);
        $session_type = new SessionType($propel_session_type);

        // Act
        $session_type->addRole('TEST_SESSION_TYPE');
    }

    /**
     * @covers \Phlopsi\AccessControl\SessionType::addRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testAddRoleException()
    {
        // Arrange
        $propel_session_type = $this->getMock(Propel\SessionType::class);
        $session_type = new SessionType($propel_session_type, $this->getFaultyConnection());

        // Act
        $session_type->addRole('TEST_SESSION_TYPE');
    }

    /**
     * @covers \Phlopsi\AccessControl\SessionType::addRole
     */
    public function testAddRole()
    {
        // Arrange
        $propel_session_type = new Propel\SessionType();
        $propel_session_type
            ->setExternalId('TEST_SESSION_TYPE')
            ->save();

        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $session_type = new Role($propel_session_type);

        // Act
        $session_type->addRole('TEST_ROLE');

        // Assert
        $this->assertEquals(1, $this->getConnection()->getRowCount(Propel\Map\RoleToSessionTypeTableMap::TABLE_NAME));
    }

    /**
     * @covers \Phlopsi\AccessControl\SessionType::removeRole
     * @expectedException \Phlopsi\AccessControl\Exception\LengthException
     */
    public function testRemoveRoleWithEmptyId()
    {
        // Arrange
        $propel_session_type = $this->getMock(Propel\SessionType::class);
        $session_type = new SessionType($propel_session_type);

        // Act
        $session_type->removeRole('');
    }

    /**
     * @covers \Phlopsi\AccessControl\SessionType::removeRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRemoveRoleWithInvalidId()
    {
        // Arrange
        $propel_session_type = $this->getMock(Propel\SessionType::class);
        $session_type = new SessionType($propel_session_type);

        // Act
        $session_type->removeRole('TEST_SESSION_TYPE');
    }

    /**
     * @covers \Phlopsi\AccessControl\SessionType::removeRole
     * @expectedException \Phlopsi\AccessControl\Exception\RuntimeException
     */
    public function testRemoveRoleException()
    {
        // Arrange
        $propel_session_type = $this->getMock(Propel\SessionType::class);
        $session_type = new SessionType($propel_session_type, $this->getFaultyConnection());

        // Act
        $session_type->removeRole('TEST_SESSION_TYPE');
    }

    /**
     * @depends testAddRole
     * @covers \Phlopsi\AccessControl\SessionType::removeRole
     * @uses \Phlopsi\AccessControl\SessionType::addRole
     */
    public function testRemoveRole()
    {
        // Arrange
        $propel_session_type = new Propel\SessionType();
        $propel_session_type
            ->setExternalId('TEST_SESSION_TYPE')
            ->save();

        $propel_role = new Propel\Role();
        $propel_role
            ->setExternalId('TEST_ROLE')
            ->save();

        $session_type = new Role($propel_session_type);
        $session_type->addRole('TEST_ROLE');

        // Act
        $session_type->removeRole('TEST_ROLE');

        // Assert
        $this->assertEquals(0, $this->getConnection()->getRowCount(Propel\Map\RoleToSessionTypeTableMap::TABLE_NAME));
    }
}
