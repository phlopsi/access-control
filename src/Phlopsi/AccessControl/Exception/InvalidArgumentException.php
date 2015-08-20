<?php

namespace Phlopsi\AccessControl\Exception;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    const ARGUMENT_IS_NOT_A_STRING = "The argument is not of type string!";
}
