<?php
namespace Phlopsi\ExceptionTranslator\Reflection;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class ExternalExceptionReflection extends ExceptionReflection
{
    /**
     * @param mixed $argument
     *
     * @throws \LogicException
     *
     * @codeCoverageIgnore
     */
    public function __construct($argument)
    {
        parent::__construct($argument);

        if ($this->isInternal()) {
            throw new \LogicException(\sprintf('Exception %s is not externally defined', $this->name));
        }
    }
}
