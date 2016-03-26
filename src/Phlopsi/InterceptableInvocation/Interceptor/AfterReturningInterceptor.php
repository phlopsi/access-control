<?php
declare(strict_types=1);

namespace Phlopsi\InterceptableInvocation\Interceptor;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface AfterReturningInterceptor {
    public function afterReturning(InvocationContext $invocationContext, $result);
}
