<?php
namespace org\bitbucket\phlopsi\access_control;

/**
 * Description of AccessControlTest
 *
 * @author Patrick
 */
class AccessControlTest extends \PHPUnit_Framework_TestCase
{
    protected $access_control;

    protected function setUp()
    {
        $this->access_control = new AccessControl();
    }

    protected function tearDown()
    {
        unset($this->access_control);
    }

    /**
     * @covers AccessControl::createPermission
     * @expectedException \InvalidArgumentException
     */
    public function testCreatePermissionInvalidArgumentException()
    {
        $this->access_control->createPermission(NULL);
    }

}
