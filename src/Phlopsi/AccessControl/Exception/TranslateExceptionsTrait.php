<?php
namespace Phlopsi\AccessControl\Exception;

use \Phlopsi\ExceptionTranslator\ExceptionTranslator;
use \Phlopsi\ExceptionTranslator\Reflection\ExceptionReflection;
use \Phlopsi\ExceptionTranslator\Reflection\ExternalExceptionReflection;

trait TranslateExceptionsTrait
{
    /**
     * @param callable $callable
     *
     * @return mixed
     *
     * @throws \Phlopsi\AccessControl\Exception\Exception
     *
     * @codeCoverageIgnore
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
