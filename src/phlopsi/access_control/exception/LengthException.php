<?php
namespace phlopsi\access_control\exception;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class LengthException extends \LengthException implements Exception
{
    const ARGUMENT_IS_EMPTY_STRING = 'The argument converts to an empty string!';
}
