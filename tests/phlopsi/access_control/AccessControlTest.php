<?php
namespace phlopsi\access_control;

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
     * @covers phlopsi\access_control\AccessControl::createPermission
     * @expectedException \InvalidArgumentException
     */
    public function testCreatePermissionInvalidArgumentException()
    {
        $this->access_control->createPermission(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::createRole
     * @expectedException \InvalidArgumentException
     */
    public function testCreateRoleInvalidArgumentException()
    {
        $this->access_control->createRole(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::createSessionType
     * @expectedException \InvalidArgumentException
     */
    public function testCreateSessionTypeInvalidArgumentException()
    {
        $this->access_control->createSessionType(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::createUser
     * @expectedException \InvalidArgumentException
     */
    public function testCreateUserInvalidArgumentException()
    {
        $this->access_control->createUser(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::deletePermission
     * @expectedException \InvalidArgumentException
     */
    public function testDeletePermissionInvalidArgumentException()
    {
        $this->access_control->deletePermission(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::deleteRole
     * @expectedException \InvalidArgumentException
     */
    public function testDeleteRoleInvalidArgumentException()
    {
        $this->access_control->deleteRole(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::deleteSessionType
     * @expectedException \InvalidArgumentException
     */
    public function testDeleteSessionTypeInvalidArgumentException()
    {
        $this->access_control->deleteSessionType(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::deleteUser
     * @expectedException \InvalidArgumentException
     */
    public function testDeleteUserInvalidArgumentException()
    {
        $this->access_control->deleteUser(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::retrieveRole
     * @expectedException \InvalidArgumentException
     */
    public function testRetrieveRoleInvalidArgumentException()
    {
        $this->access_control->retrieveRole(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::retrieveSessionType
     * @expectedException \InvalidArgumentException
     */
    public function testRetrieveSessionTypeInvalidArgumentException()
    {
        $this->access_control->retrieveSessionType(NULL);
    }

    /**
     * @covers phlopsi\access_control\AccessControl::retrieveUser
     * @expectedException \InvalidArgumentException
     */
    public function testRetrieveUserInvalidArgumentException()
    {
        $this->access_control->retrieveUser(NULL);
    }
}
