<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Propel\Generator\Util\SqlParser;

class UserTest extends \PHPUnit_Extensions_Database_TestCase
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
     * @covers \Phlopsi\AccessControl\User::hasPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testHasPermissionWithEmptyId()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);

        // Expect
        $this->setExpectedException(LengthException::class);

        // Act
        $user->hasPermission('');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testHasPermissionWithInvalidId()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $user->hasPermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testHasPermissionException()
    {
        // Arrange
        $propel_user = $this->getMock(Propel\User::class);
        $user = new User($propel_user);
        $user->setConnection($this->getFaultyConnection());

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $user->hasPermission('TEST_PERMISSION');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
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
        $this->assertFalse($has_permission, 'Assert that User::hasPermission returns false');
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
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
        $this->assertTrue($has_permission, 'Assert that User::hasPermission returns true');
    }
}
