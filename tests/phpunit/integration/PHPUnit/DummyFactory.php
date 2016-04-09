<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\PHPUnit;

use Phlopsi\AccessControl\Permission;
use Phlopsi\AccessControl\Propel\Base\BasePermissionRepository;
use Phlopsi\AccessControl\Propel\Permission as PropelPermission;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount as AnyInvokedCount;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_TestCase as TestCase;

class DummyFactory
{
    /**
     * @var \PHPUnit_Framework_TestCase
     */
    private $testCase;

    /**
     * @param \PHPUnit_Framework_TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @return \Phlopsi\AccessControl\Propel\Base\BasePermissionRepository
     */
    public function getBasePermissionRepository(): BasePermissionRepository
    {
        $dummy = (new MockBuilder($this->testCase, BasePermissionRepository::class))
            ->disableOriginalConstructor()
            ->getMock();

        $dummy
            ->expects(new AnyInvokedCount())
            ->method('createObject')
            ->willReturn($this->getPropelPermission());

        $dummy
            ->expects(new AnyInvokedCount())
            ->method('createQuery')
            ->willReturn($this->getPropelPermissionQuery());

        return $dummy;
    }

    /**
     * @return \Phlopsi\AccessControl\Permission
     */
    public function getPermission(): Permission
    {
        $dummy = (new MockBuilder($this->testCase, Permission::class))
            ->disableOriginalConstructor()
            ->getMock();

        return $dummy;
    }

    /**
     * @return \Phlopsi\AccessControl\Propel\Permission
     */
    public function getPropelPermission(): PropelPermission
    {
        $dummy = (new MockBuilder($this->testCase, PropelPermission::class))
            ->disableOriginalConstructor()
            ->getMock();

        return $dummy;
    }

    /**
     * @return \Phlopsi\AccessControl\Propel\PermissionQuery
     */
    public function getPropelPermissionQuery(): PropelPermissionQuery
    {
        $dummy = (new MockBuilder($this->testCase, PropelPermissionQuery::class))
            ->disableOriginalConstructor()
            ->getMock();

        $dummy
            ->expects(new AnyInvokedCount())
            ->method('requireOneByExternalId')
            ->willReturn($this->getPropelPermission());

        return $dummy;
    }
}
