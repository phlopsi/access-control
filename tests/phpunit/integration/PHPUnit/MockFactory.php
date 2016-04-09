<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\PHPUnit;

use Phlopsi\AccessControl\Propel\Base\BasePermissionRepository;
use Phlopsi\AccessControl\Propel\Permission as PropelPermission;
use Phlopsi\AccessControl\Propel\PermissionQuery as PropelPermissionQuery;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;

class MockFactory
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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getBasePermissionRepository(): MockObject
    {
        $mock = (new MockBuilder($this->testCase, BasePermissionRepository::class))
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getPropelPermission(): MockObject
    {
        $mock = (new MockBuilder($this->testCase, PropelPermission::class))
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getPropelPermissionQuery(): MockObject
    {
        $mock = (new MockBuilder($this->testCase, PropelPermissionQuery::class))
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
