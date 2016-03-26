<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\ExceptionTranslator;

use Phlopsi\Reflection\ExceptionReflection;
use Phlopsi\Reflection\UserDefinedExceptionReflection;

class ExceptionTranslator
{
    /**
     * @var \Phlopsi\Reflection\UserDefinedExceptionReflection
     */
    private $default_exception_reflection;

    /**
     * @var string
     */
    private $exception_namespace;

    /**
     * @param \Phlopsi\Reflection\UserDefinedExceptionReflection $default_exception_reflection
     *
     * @codeCoverageIgnore
     */
    public function __construct(UserDefinedExceptionReflection $default_exception_reflection)
    {
        $this->default_exception_reflection = $default_exception_reflection;
        $this->exception_namespace = $default_exception_reflection->getNamespaceName();
    }

    /**
     * @param \Exception $exception
     *
     * @return \Exception
     */
    public function translate(\Exception $exception): \Exception
    {
        $exception_reflection = new ExceptionReflection($exception);

        if ($exception_reflection->getNamespaceName() === $this->exception_namespace) {
            return $exception;
        }

        $current_exception_reflection = $exception_reflection;

        do {
            $exception_name = $this->exception_namespace . '\\' . $current_exception_reflection->getShortName();

            if (\class_exists($exception_name)) {
                return new $exception_name(
                    $exception->getMessage(),
                    $exception->getCode(),
                    $exception
                );
            }
        } while ($current_exception_reflection = $current_exception_reflection->getParentClass());

        return $this->default_exception_reflection->newInstance(
            $exception->getMessage(),
            $exception->getCode(),
            $exception
        );
    }
}
