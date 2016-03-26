<?php
declare(strict_types=1);

namespace Phlopsi\InterceptableInvocation\Interceptor;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface BeforeInvocationInterceptor {
    public function beforeInvocation(InvocationContext $invocationContext, &$result): bool;
}
