<?php
declare(strict_types=1);

namespace Phlopsi\InterceptableInvocation\Utility;

/**
 * Provides additional information for a callable
 *
 * @see https://secure.php.net/manual/en/language.types.callable.php
 *
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class CallableInfo
{
    /**
     * Contains the callable for which information is requested
     *
     * MUST NOT be changed after initialization
     *
     * @var callable
     *
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableInfo::__construct() Initializes this property
     */
    private $callable;

    /**
     * Contains the type of the callable after the first call to `$this->detectType()`
     *
     * @var \Phlopsi\InterceptableInvocation\Utility\CallableType|null
     *
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableInfo::detectType() Changes this property
     */
    private $type;

    /**
     * @param callable $callable Callable for which information is requested
     *
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableInfo::$callable
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableInfo::$type
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
        $this->type = null;
    }

    /**
     * Detects and returns the type of the provided callable
     *
     * @return \Phlopsi\InterceptableInvocation\Utility\CallableType The detected type
     *
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableInfo::$callable
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableInfo::$type
     */
    public function detectType(): CallableType
    {
        if (null === $this->type) {
            if (\is_string($this->callable)) {
                if (false === \strpos($this->callable, '::')) {
                    $this->type = CallableType::$FUNCTION;
                } else {
                    $this->type = CallableType::$STATIC_CLASS_METHOD;
                }
            } elseif (\is_array($this->callable)) {
                $is_object_method = \is_object($this->callable[0]);
                $is_relative_method = (false !== \strpos($this->callable[1], '::'));

                if ($is_object_method && $is_relative_method) {
                    $this->type = CallableType::$RELATIVE_OBJECT_METHOD;
                } elseif ($is_object_method && !$is_relative_method) {
                    $this->type = CallableType::$OBJECT_METHOD;
                } elseif (!$is_object_method && $is_relative_method) {
                    $this->type = CallableType::$RELATIVE_STATIC_CLASS_METHOD;
                } else {
                    $this->type = CallableType::$STATIC_CLASS_METHOD;
                }
            } elseif ($this->callable instanceof \Closure) {
                $this->type = CallableType::$CLOSURE;
            } else {
                $this->type = CallableType::$INVOCABLE_OBJECT;
            }
        }

        return $this->type;
    }
}
