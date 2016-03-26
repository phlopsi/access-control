<?php
declare(strict_types=1);

namespace Phlopsi\InterceptableInvocation;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class InterceptableCallableInvocation
{
    private $interceptor_manager;
    private $callable;

    public function __construct(InterceptorManager $interceptorManager, callable $callable)
    {
        $this->interceptor_manager = $interceptorManager;
        $this->callable = $callable;
    }

    public function __invoke(...$arguments)
    {
        try {
            $this->interceptor_manager->beforeInvocation();
            $result = \call_user_func_array($this->callable, $arguments);
            $this->interceptor_manager->afterReturning();
        } catch (\Throwable $throwable) {
            $this->interceptor_manager->afterThrowing();
        } finally {
            $this->interceptor_manager->afterInvocation();
        }
    }
}
