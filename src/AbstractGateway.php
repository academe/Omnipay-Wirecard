<?php

namespace Omnipay\Wirecard;

/**
 * Wirecard (driver for Omnipay
 */

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\AbstractGateway as OmnipayAbstractGateway;

abstract class AbstractGateway extends OmnipayAbstractGateway
{
    /**
     *
     */
    public function getDefaultParameters()
    {
        return array(
            // Required. The merchant ID.
            'customerId' => '',
            // Required if more than one shop is in the account.
            'shopId' => '',
            // Pre-share key used to hash data.
            'secret' => '',
            // Required.
            'language' => 'en',
        );
    }

    /**
     * *** Global Settings ***
     */

    /**
     * The Customer ID is always needed.
     */
    public function setCustomerId($customerId)
    {
        if (!is_string($customerId)) {
            throw new InvalidRequestException('Customer ID must be a string.');
        }

        return $this->setParameter('customerId', $customerId);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    /**
     * The Shop ID is needed if there is more than one shop to access.
     */
    public function setShopId($shopId)
    {
        if (!is_string($shopId)) {
            throw new InvalidRequestException('Shop ID must be a string.');
        }

        return $this->setParameter('shopId', $shopId);
    }

    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    /**
     * The Secret is always needed.
     */
    public function setSecret($secret)
    {
        return $this->setParameter('secret', $secret);
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
}
