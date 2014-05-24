<?php

namespace phlopsi\access_control\exception;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class RuntimeException extends \RuntimeException implements Exception
{
    const ENTITY_ALREADY_EXISTS = '"%s" already exists!';
    const ENTITY_NOT_FOUND = '"%s" not found!';
}
