<?php
declare(strict_types=1);

namespace Phlopsi\Reflection;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class UserDefinedExceptionReflection extends ExceptionReflection
{
    /**
     * @param mixed $argument
     */
    public function __construct($argument)
    {
        parent::__construct($argument);

        if (!$this->isUserDefined()) {
            throw new \DomainException(\sprintf('Exception `%s` is not user defined', $this->name));
        }
    }
}
