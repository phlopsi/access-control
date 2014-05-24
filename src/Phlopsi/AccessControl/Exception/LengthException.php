<?php
namespace Phlopsi\AccessControl\Exception;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class LengthException extends \LengthException implements ExceptionInterface
{
    const ARGUMENT_IS_EMPTY_STRING = 'The argument converts to an empty string!';
}
