<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\ExceptionTranslator;

use Phlopsi\ExceptionTranslator\Reflection\ExceptionReflection;
use Phlopsi\ExceptionTranslator\Reflection\ExternalExceptionReflection;

class ExceptionTranslator
{
    /**
     * @var ExternalExceptionReflection
     */
    private $default_exception_reflection;
    
    /**
     * @var string
     */
    private $exception_namespace;

    /**
     * @param ExternalExceptionReflection $default_exception_reflection
     *
     * @throws \LogicException
     *
     * @codeCoverageIgnore
     */
    public function __construct(ExternalExceptionReflection $default_exception_reflection)
    {
        $this->default_exception_reflection = $default_exception_reflection;
        $this->exception_namespace = $default_exception_reflection->getNamespaceName();
    }

    /**
     * @param ExceptionReflection $exception_reflection
     *
     * @return ExternalExceptionReflection
     *
     * @codeCoverageIgnore
     */
    public function translate(ExceptionReflection $exception_reflection)
    {
        $current_exception_reflection = $exception_reflection;
        
        do {
            $exception_name = $this->exception_namespace . '\\' . $current_exception_reflection->getShortName();
            
            if (\class_exists($exception_name)) {
                return new ExternalExceptionReflection($exception_name);
            }
        } while ($current_exception_reflection = $current_exception_reflection->getParentClass());
        
        return $this->default_exception_reflection;
    }
}
