<?php
declare(strict_types=1);

namespace Phlopsi\InterceptableInvocation\Interceptor;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface AfterInvocationInterceptor {
    public function afterInvocation(InvocationContext $invocationContext);
}
