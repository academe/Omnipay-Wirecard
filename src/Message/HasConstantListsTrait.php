<?php

namespace Omnipay\Wirecard\Message;

/**
 * Provides access to lists stored as constants.
 */

use ReflectionClass;

trait HasConstantListsTrait
{
    /**
     * Get an array of constants in this [late-bound] class, with an optional prefix.
     *
     * @param null|string $prefix
     * @return array
     */
    public static function constantList($prefix = null)
    {
        $reflection = new ReflectionClass(get_called_class());

        $constants = $reflection->getConstants();

        if (isset($prefix)) {
            $result = [];
            $prefix = strtoupper($prefix);
            foreach($constants as $key => $value) {
                if (strpos($key, $prefix) === 0) {
                    $result[$key] = $value;
                }
            }
            return $result;
        } else {
            return $constants;
        }
    }

    /**
     * Get a class constant value based on suffix and prefix.
     * Returns null if not found.
     *
     * @param $prefix
     * @param $suffix
     * @return mixed|null
     */
    public static function constantValue($prefix, $suffix)
    {
        $name = strtoupper($prefix . '_' . $suffix);
        if (defined("static::$name")) {
            return constant("static::$name");
        }

        return null;
    }
}
