<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Propel\Base\BasePermissionRepository;
use Phlopsi\AccessControl\Propel\Base\BaseRoleRepository;
use Phlopsi\AccessControl\Propel\Base\BaseUserRepository;
use Phlopsi\AccessControl\Repository\PermissionRepository;
use Phlopsi\AccessControl\Repository\RoleRepository;
use Phlopsi\AccessControl\Repository\UserRepository;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

class AccessControlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \Phlopsi\AccessControl\Propel\Base\BasePermissionRepository
     */
    private function getBasePermissionRepositoryDummy()
    {
        return (new MockBuilder($this, BasePermissionRepository::class))
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Phlopsi\AccessControl\Propel\Base\BaseRoleRepository
     */
    private function getBaseRoleRepositoryDummy()
    {
        return (new MockBuilder($this, BaseRoleRepository::class))
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Phlopsi\AccessControl\Propel\Base\BaseUserRepository
     */
    private function getBaseUserRepositoryDummy()
    {
        return (new MockBuilder($this, BaseUserRepository::class))
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::getPermissionRepository()
     */
    public function testGetPermissionRepository()
    {
        // Arrange
        $basePermissionRepository = $this->getBasePermissionRepositoryDummy();
        $accessControl = new AccessControl($basePermissionRepository);

        // Act
        $result = $accessControl->getPermissionRepository();

        // Assert
        $this->assertInstanceOf(PermissionRepository::class, $result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::getRoleRepository()
     */
    public function testGetRoleRepository()
    {
        // Arrange
        $baseRoleRepository = $this->getBaseRoleRepositoryDummy();
        $accessControl = new AccessControl($baseRoleRepository);

        // Act
        $result = $accessControl->getRoleRepository();

        // Assert
        $this->assertInstanceOf(RoleRepository::class, $result);
    }

    /**
     * @covers \Phlopsi\AccessControl\AccessControl::getPermissionRepository()
     */
    public function testGetUserRepository()
    {
        // Arrange
        $baseUserRepository = $this->getBaseUserRepositoryDummy();
        $accessControl = new AccessControl($baseUserRepository);

        // Act
        $result = $accessControl->getUserRepository();

        // Assert
        $this->assertInstanceOf(UserRepository::class, $result);
    }
}