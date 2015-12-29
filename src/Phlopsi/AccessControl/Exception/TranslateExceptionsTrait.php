<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl\Exception;

use \Phlopsi\ExceptionTranslator\ExceptionTranslator;
use \Phlopsi\Reflection\ExceptionReflection;
use \Phlopsi\Reflection\ExternalExceptionReflection;

trait TranslateExceptionsTrait
{
    /**
     * @param callable $callable
     *
     * @return mixed
     *   Same return type as $callable
     *
     * @throws \Phlopsi\AccessControl\Exception\Exception
     */
    private function execute(callable $callable)
    {
        try {
            return $callable();
        } catch (Exception $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $default_exception_reflection = new ExternalExceptionReflection(RuntimeException::class);
            $exception_translator = new ExceptionTranslator($default_exception_reflection);
            $exception_reflection = new ExceptionReflection($exception);
            $translated_exception_reflection = $exception_translator->translate($exception_reflection);
            
            throw $translated_exception_reflection->newInstance(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
