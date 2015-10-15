<?php
namespace Phlopsi\ExceptionTranslator\Reflection;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class ExceptionReflection extends \ReflectionClass
{
    /**
     * @param mixed $argument
     *
     * @throws \LogicException
     */
    public function __construct($argument)
    {
        try {
            parent::__construct($argument);
        } catch (\ReflectionException $exception) {
            throw new \LogicException($exception->getMessage(), $exception->getCode(), $exception);
        }

        if (!(\Exception::class === $this->name || $this->isSubclassOf(\Exception::class))) {
            throw new \LogicException(\sprintf('Class %s is not an Exception', $this->name));
        }
    }
}
