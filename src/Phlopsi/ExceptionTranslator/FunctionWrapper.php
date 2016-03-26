<?php
declare(strict_types=1);

namespace Phlopsi\ExceptionTranslator;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class FunctionWrapper
{
    private $exception_translator;

    public function __construct(ExceptionTranslator $exception_translator)
    {
        $this->exception_translator = $exception_translator;
    }

    /**
     * @param callable $callable
     *
     * @return mixed
     *   Same return type as $callable
     */
    protected function __invoke(callable $callable)
    {
        try {
            return $callable();
        } catch (\Exception $exception) {
            $translated_exception = $this->exception_translator->translate($exception);

            throw $translated_exception;
        }
    }
}
