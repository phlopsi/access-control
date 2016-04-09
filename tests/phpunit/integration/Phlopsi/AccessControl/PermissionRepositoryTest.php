<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Exception\Exception;

class PermissionRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Phlopsi\PHPUnit\DummyFactory
     */
    private $dummyFactory;

    /**
     * @var \Phlopsi\PHPUnit\MockFactory
     */
    private $mockObjectFactory;

    protected function setUp()
    {
        $this->dummyFactory = new \Phlopsi\PHPUnit\DummyFactory($this);
        $this->mockObjectFactory = new \Phlopsi\PHPUnit\MockFactory($this);
    }

    protected function tearDown()
    {
        $this->dummyFactory = null;
        $this->mockObjectFactory = null;
    }

    /**
     * @covers \Phlopsi\AccessControl\PermissionRepository::create()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreatePermissionWithEmptyId()
    {
        // Arrange
        $propel_permission_repository = $this->dummyFactory->getBasePermissionRepository();
        $permission_repository = new PermissionRepository($propel_permission_repository);

        // Expect
        $this->expectedException(Exception::class);

        // Act
        $permission_repository->create('');
    }

    /**
     * @covers \Phlopsi\AccessControl\PermissionRepository::create()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testCreatePermission()
    {
        // Arrange
        $propel_permission_repository = $this->mockObjectFactory->getBasePermissionRepository();
        $propel_permission_repository
            ->expects($this->once())
            ->method('createObject')
            ->willReturn($this->dummyFactory->getPropelPermission());

        $propel_permission_repository
            ->expects($this->once())
            ->method('save');

        $permission_repository = new PermissionRepository($propel_permission_repository);

        // Act
        $result = $permission_repository->create('TEST_PERMISSION');

        // Assert
        $message = 'Assert that %s::create() returns an object of type %s';
        $message = \sprintf($message, DefaultPermissionRepository::class, Permission::class);
        $this->assertTrue(\is_object($result), $message);
        $this->assertInstanceOf(Permission::class, $result, $message);
    }

    /**
     * @covers \Phlopsi\AccessControl\PermissionRepository::delete()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testDeletePermission()
    {
        // Arrange
        $propel_permission_repository = $this->mockObjectFactory->getBasePermissionRepository();
        $propel_permission_repository
            ->expects($this->once())
            ->method('remove')
            ->willReturn($this->dummyFactory->getPropelPermission());

        $permission_repository = new PermissionRepository($propel_permission_repository);
        $permission = $this->dummyFactory->getPermission();

        // Act
        $permission_repository->delete($permission);
    }

    /**
     * @covers \Phlopsi\AccessControl\PermissionRepository::retrieve()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrievePermissionWithEmptyId()
    {
        // Arrange
        $propel_permission_repository = $this->dummyFactory->getBasePermissionRepository();
        $permission_repository = new PermissionRepository($propel_permission_repository);

        // Expect
        $this->setExpectedException(Exception::class);

        // Act
        $permission_repository->retrieve('');
    }

    /**
     * @covers \Phlopsi\AccessControl\PermissionRepository::retrieve()
     * @uses \Phlopsi\AccessControl\TranslateExceptionsTrait::execute()
     */
    public function testRetrievePermission()
    {
        // Arrange
        $test_permission_id = 'TEST_PERMISSION';

        $propel_permission_query = $this->mockObjectFactory->getPropelPermissionQuery();
        $propel_permission_query
            ->expects($this->any())
            ->method('__call')
            ->with($this->stringEndsWith('OneByExternalId'), $this->equalTo([$test_permission_id]))
            ->willReturn($this->dummyFactory->getPropelPermission());

        $propel_permission_repository = $this->mockObjectFactory->getBasePermissionRepository();
        $propel_permission_repository
            ->expects($this->once())
            ->method('createQuery')
            ->willReturn($propel_permission_query);

        $permission_repository = new PermissionRepository($propel_permission_repository);

        // Act
        $result = $permission_repository->retrieve($test_permission_id);

        // Assert
        $message = 'Assert that %s::retrieve() returns an object of type %s';
        $message = \sprintf($message, DefaultPermissionRepository::class, Permission::class);
        $this->assertTrue(\is_object($result), $message);
        $this->assertInstanceOf(Permission::class, $result, $message);
    }
}
