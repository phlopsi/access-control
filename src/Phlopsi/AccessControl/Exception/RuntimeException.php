<?php

namespace Phlopsi\AccessControl\Exception;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class RuntimeException extends \RuntimeException implements Exception
{
    const ENTITY_NOT_FOUND = '"%s" not found!';
}
