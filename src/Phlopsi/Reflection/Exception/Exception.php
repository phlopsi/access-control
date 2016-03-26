<?php
declare(strict_types=1);

namespace Phlopsi\Reflection\Exception;

interface Exception
{
    public function __toString();
    public function getCode();
    public function getFile();
    public function getLine();
    public function getMessage();
    public function getPrevious();
    public function getTrace();
    public function getTraceAsString();
}
