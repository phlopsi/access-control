<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

namespace Phlopsi\ExceptionTranslator\Reflection;

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
