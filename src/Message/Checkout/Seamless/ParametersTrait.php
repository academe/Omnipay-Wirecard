<?php

namespace Omnipay\Wirecard\Message\Checkout\Seamless;

/**
 * Manage parameters shared betweem the gateway and the message levels.
 * These are parameters specific to Checkout Seamless only.
 */

use Omnipay\Common\Exception\InvalidRequestException;

trait ParametersTrait
{
    // Data for setting up the front end.

    /**
     * Unique ID of order which has to be the same as used for initiating
     * the data storage.
     * Alphanumeric with special characters.
     */
    public function setOrderIdent($value)
    {
        return $this->setParameter('orderIdent', $value);
    }

    public function getOrderIdent()
    {
        return $this->getParameter('orderIdent');
    }

    /**
     * Unique ID of data storage for a specific consumer.
     * Alphanumeric with a fixed length of 32.
     */
    public function setStorageId($value)
    {
        return $this->setParameter('storageId', $value);
    }

    public function getStorageId()
    {
        return $this->getParameter('storageId');
    }

    /**
     * Version number of JavaScript.
     * Alphanumeric.
     */
    public function setJavascriptScriptVersion($value)
    {
        return $this->setParameter('javascriptScriptVersion', $value);
    }

    public function getJavascriptScriptVersion()
    {
        return $this->getParameter('javascriptScriptVersion');
    }

    // Data to pass on through the backend.

    /**
     * User-agent of browser of consumer.
     * Alphanumeric with special characters.
     */
    public function setConsumerUserAgent($value)
    {
        return $this->setParameter('consumerUserAgent', $value);
    }

    public function getConsumerUserAgent()
    {
        return $this->getParameter('consumerUserAgent');
    }
}
