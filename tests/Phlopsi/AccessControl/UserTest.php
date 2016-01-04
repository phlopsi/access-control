<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Exception\RuntimeException;
use Phlopsi\AccessControl\Propel\Permission as PropelPermission;
use Phlopsi\AccessControl\Propel\Role as PropelRole;
use Phlopsi\AccessControl\Propel\User as PropelUser;
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
    public function testHasPermissionException()
    {
        // Arrange
        $propel_user = $this->getMock(PropelUser::class);
        $user = new User($propel_user);
        $user->setConnection($this->getFaultyConnection());

        $propel_permission = $this->getMock(PropelPermission::class);
        $permission = new Permission($propel_permission);

        // Expect
        $this->setExpectedException(RuntimeException::class);

        // Act
        $user->hasPermission($permission);
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testHasPermissionFalse()
    {
        // Arrange
        $propel_user = new PropelUser();
        $propel_user
            ->setExternalId('TEST_USER')
            ->save();

        $user = new User($propel_user);

        $propel_permission = new PropelPermission();
        $propel_permission
            ->setExternalId('TEST_PERMISSION')
            ->save();

        $permission = new Permission($propel_permission);

        // Act
        $has_permission = $user->hasPermission($permission);

        // Assert
        $message = \sprintf('Assert that %s::hasPermission() returns false', User::class);
        $this->assertFalse($has_permission, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\User::hasPermission()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testHasPermissionTrue()
    {
        // Arrange
        $propel_user = new PropelUser();
        $propel_user
            ->setExternalId('TEST_USER')
            ->save();

        $user = new User($propel_user);

        $propel_permission = new PropelPermission();
        $propel_permission
            ->setExternalId('TEST_PERMISSION')
            ->save();

        $permission = new Permission($propel_permission);

        (new PropelRole())
            ->setExternalId('TEST_ROLE')
            ->addPermission($propel_permission)
            ->addUser($propel_user)
            ->save();

        // Act
        $has_permission = $user->hasPermission($permission);

        // Assert
        $message = \sprintf('Assert that %s::hasPermission() returns true', User::class);
        $this->assertTrue($has_permission, $message);
    }
}
