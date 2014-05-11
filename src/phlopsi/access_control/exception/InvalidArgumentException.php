<?php
namespace phlopsi\access_control\exception;

class InvalidArgumentException extends \InvalidArgumentException implements Exception
{
    const ARGUMENT_IS_EMPTY_STRING = 'The argument converts to an empty string!';
}
