<?php
declare(strict_types=1);

namespace Phlopsi\Enumeration;

/**
 * Defines common enumeration behaviour
 *
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
abstract class AbstractEnumeration implements Enumeration
{
    /**
     * Used by `self::initialize()`
     *
     * @var bool
     *
     * @see \Phlopsi\InterceptableInvocation\Utility\AbstractEnumeration::initialize()
     */
    private static $initialized = false;

    /**
     * Represents the enumeration property as a string
     *
     * MUST NOT be changed after instantiation
     *
     * @var string
     *
     * @see \Phlopsi\InterceptableInvocation\Utility\AbstractEnumeration::__construct() Initializes this property
     */
    private $value;

    /**
     * @inheritDoc
     */
    protected static function initialize()
    {
        if (self::$initialized) {
            throw new \LogicException(sprintf('`%s` is already initialized', static::class));
        }

        self::$initialized = true;
    }

    /**
     * Disables the `__set_state()` magic method
     *
     * `final private` due to pseudo enumeration behaviour
     *
     * @param array $array
     */
    final private static function __set_state(array $array) {}

    /**
     * Disables the `__clone()` magic method
     *
     * `final private` due to pseudo enumeration behaviour
     */
    final private function __clone() {}

    /**
     * Instantiates an enumeration property
     *
     * `protected` due to pseudo enumeration behaviour
     * MUST only be called by `self::initialize()`
     *
     * @param string $value The string representation of the enumeration property
     *
     * @see \Phlopsi\InterceptableInvocation\Utility\AbstractEnumeration::initialize()
     */
    final protected function __construct(string $value)
    {
        $this->value = static::class . '::' . $value;
    }

    /**
     * @inheritDoc
     */
    final public function __toString(): string
    {
        return $this->value;
    }
}
