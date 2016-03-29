<?php
declare(strict_types=1);

namespace Phlopsi\Enumeration;

/**
 * Common interface for enumerations
 *
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface Enumeration
{
    /**
     * Initializes the enumeration properties
     */
    public static function initialize();

    /**
     * Returns the string representation of the enumeration property
     *
     * @return string
     */
    public function __toString(): string;
}
