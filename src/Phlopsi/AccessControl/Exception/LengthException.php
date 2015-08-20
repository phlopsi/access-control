<?php
namespace Phlopsi\AccessControl\Exception;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class LengthException extends \LengthException implements ExceptionInterface
{
    const ARGUMENT_IS_EMPTY_STRING = 'The argument is an empty string, which is not allowed!';
}
