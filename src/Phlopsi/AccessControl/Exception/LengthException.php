<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl\Exception;

class LengthException extends \LengthException implements Exception
{
    const CODE_ARGUMENT_IS_EMPTY_STRING = 1;

    const MESSAGE_ARGUMENT_IS_EMPTY_STRING = 'The provided argument is an empty string';
}
