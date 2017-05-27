<?php

namespace Omnipay\Wirecard;

/**
 * Manage parameters shared betweem the gateway and the message levels.
 */

use Omnipay\Common\Exception\InvalidRequestException;

trait HasGatewayParametersTrait
{
    /**
     * The Customer ID is the merchant account ID string.
     */
    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    /**
     * The Shop ID.
     * Leave empty if there is only one shop set up.
     */
    public function setShopId($value)
    {
        return $this->setParameter('shopId', $value);
    }

    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    /**
     * The secret for hashing.
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * The language sets the language used in the customermessage results..
     */
    public function setLanguage($language)
    {
        return $this->setParameter('language', $language);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * The Toolkit Password.
     * Used only for backend functions.
     */
    public function setToolkitPassword($toolkitPassword)
    {
        if (!is_string($toolkitPassword)) {
            throw new InvalidRequestException('Toolkit Password must be a string.');
        }

        return $this->setParameter('toolkitPassword', $toolkitPassword);
    }

    public function getToolkitPassword()
    {
        return $this->getParameter('toolkitPassword');
    }

    /**
     * Get the request failure URL.
     *
     * @return string
     */
    public function getFailureUrl()
    {
        return $this->getParameter('failureUrl');
    }

    /**
     * Sets the request failure URL.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setFailureUrl($value)
    {
        return $this->setParameter('failureUrl', $value);
    }

    /**
     * Get the request service URL.
     *
     * @return string
     */
    public function getServiceUrl()
    {
        return $this->getParameter('serviceUrl');
    }

    /**
     * Sets the request service URL.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setServiceUrl($value)
    {
        return $this->setParameter('serviceUrl', $value);
    }
}
