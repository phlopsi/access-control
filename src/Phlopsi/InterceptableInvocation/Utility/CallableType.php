<?php
declare(strict_types=1);

namespace Phlopsi\InterceptableInvocation\Utility;

use Phlopsi\Enumeration\AbstractEnumeration;
use Phlopsi\Enumeration\Enumeration;

/**
 * Type-safe pseudo enumeration of a callable type
 *
 * @see https://secure.php.net/manual/en/language.types.callable.php
 *
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
final class CallableType extends AbstractEnumeration implements Enumeration
{
    /**
     * @var CallableType
     */
    public static $CLOSURE;

    /**
     * @var CallableType
     */
    public static $FUNCTION;

    /**
     * @var CallableType
     */
    public static $INVOCABLE_OBJECT;

    /**
     * @var CallableType
     */
    public static $OBJECT_METHOD;

    /**
     * @var CallableType
     */
    public static $RELATIVE_OBJECT_METHOD;

    /**
     * @var CallableType
     */
    public static $RELATIVE_STATIC_CLASS_METHOD;

    /**
     * @var CallableType
     */
    public static $STATIC_CLASS_METHOD;

    /**
     * @inheritDoc
     *
     * @uses \Phlopsi\InterceptableInvocation\Utility\CallableType::__construct()
     */
    public static function initialize()
    {
        parent::initialize();

        self::$CLOSURE = new self('CLOSURE');
        self::$FUNCTION = new self('FUNCTION');
        self::$INVOCABLE_OBJECT = new self('INVOCABLE_OBJECT');
        self::$OBJECT_METHOD = new self('OBJECT_METHOD');
        self::$RELATIVE_OBJECT_METHOD = new self('RELATIVE_OBJECT_METHOD');
        self::$RELATIVE_STATIC_CLASS_METHOD = new self('RELATIVE_STATIC_CLASS_METHOD');
        self::$STATIC_CLASS_METHOD = new self('STATIC_CLASS_METHOD');
    }
}

CallableType::initialize();
