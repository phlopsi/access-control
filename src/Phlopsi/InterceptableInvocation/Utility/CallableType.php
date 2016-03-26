<?php
declare(strict_types=1);

namespace Phlopsi\InterceptableInvocation\Utility;

/**
 * Type-safe pseudo enumeration of a callable type
 *
 * @see https://secure.php.net/manual/en/language.types.callable.php
 *
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
final class CallableType extends AbstractEnumeration
{
    /**
     * @var bool
     */
    private static $initialized = false;

    /**
     * @var \Phlopsi\InterceptableInvocation\Utility\CallableType
     */
    public static $CLOSURE;

    /**
     * @var \Phlopsi\InterceptableInvocation\Utility\CallableType
     */
    public static $FUNCTION;

    /**
     * @var \Phlopsi\InterceptableInvocation\Utility\CallableType
     */
    public static $INVOCABLE_OBJECT;

    /**
     * @var \Phlopsi\InterceptableInvocation\Utility\CallableType
     */
    public static $OBJECT_METHOD;

    /**
     * @var \Phlopsi\InterceptableInvocation\Utility\CallableType
     */
    public static $RELATIVE_OBJECT_METHOD;

    /**
     * @var \Phlopsi\InterceptableInvocation\Utility\CallableType
     */
    public static $RELATIVE_STATIC_CLASS_METHOD;

    /**
     * @var \Phlopsi\InterceptableInvocation\Utility\CallableType
     */
    public static $STATIC_CLASS_METHOD;

    /**
     * @inheritDoc
     *
     * @uses \Phlopsi\InterceptableInvocation\Utility\CallableType::__construct()
     *
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableType::$CLOSURE
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableType::$FUNCTION
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableType::$INVOCABLE_OBJECT
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableType::$OBJECT_METHOD
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableType::$RELATIVE_OBJECT_METHOD
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableType::$RELATIVE_STATIC_CLASS_METHOD
     * @see \Phlopsi\InterceptableInvocation\Utility\CallableType::$STATIC_CLASS_METHOD
     */
    public static function initialize()
    {
        if (self::$initialized) {
            throw new \LogicException(sprintf('`%s` is already initialized', self::class));
        }

        self::$CLOSURE = new self('CLOSURE');
        self::$FUNCTION = new self('FUNCTION');
        self::$INVOCABLE_OBJECT = new self('INVOCABLE_OBJECT');
        self::$OBJECT_METHOD = new self('OBJECT_METHOD');
        self::$RELATIVE_OBJECT_METHOD = new self('RELATIVE_OBJECT_METHOD');
        self::$RELATIVE_STATIC_CLASS_METHOD = new self('RELATIVE_STATIC_CLASS_METHOD');
        self::$STATIC_CLASS_METHOD = new self('STATIC_CLASS_METHOD');

        self::$initialized = true;
    }
}

CallableType::initialize();
