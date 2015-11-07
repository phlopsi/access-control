<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\AccessControl\Exception;

class RuntimeException extends \RuntimeException implements Exception
{
    const ENTITY_NOT_FOUND = '"%s" not found!';
}
