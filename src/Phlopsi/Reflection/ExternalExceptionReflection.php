<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\Reflection;

class ExternalExceptionReflection extends ExceptionReflection
{
    /**
     * @param mixed $argument
     *
     * @throws \DomainException
     */
    public function __construct($argument)
    {
        parent::__construct($argument);

        if ($this->isInternal()) {
            throw new \DomainException(\sprintf('Exception %s is not externally defined', $this->name));
        }
    }
}
