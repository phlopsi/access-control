<?php
class WarningTest extends \PHPUnit_Framework_TestCase
{
    public function testWarning() {
        $this->expectException(PHPUnit_Framework_Error_Warning::class);
        $this->expectExceptionMessage('I am an assertion!');
        assert(false, 'I am an assertion!');
    }
}
