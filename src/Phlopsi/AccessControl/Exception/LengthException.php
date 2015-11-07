<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl\Exception;

class LengthException extends \LengthException implements Exception
{
    const ARGUMENT_IS_EMPTY_STRING = 'The argument is an empty string, which is not allowed!';
}
