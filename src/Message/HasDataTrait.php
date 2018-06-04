<?php

namespace Omnipay\Wirecard\Message;

/**
 *
 */

trait HasDataTrait
{
    /**
     * Get a single data value from the ServerRequest data.
     */
    protected function getDataValue($name, $default = null)
    {
        $data = $this->getData();
        $value = array_key_exists($name, $data) ? $data[$name] : $default;

        return $value;
    }
}
