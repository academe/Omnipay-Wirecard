<?php

namespace Omnipay\Wirecard;

/**
 * Wirecard Checkout Seamless driver for Omnipay
 */

use Omnipay\Wirecard\Traits\CheckoutSeamlessParametersTrait;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Wirecard\Traits\CheckoutParametersTrait;

class CheckoutSeamlessGateway extends AbstractGateway
{
    // Custom parameters implemented for the Checkout APIs.
    use CheckoutParametersTrait;

    // Custom parameters implemented for the Checkout Seamless API.
    use CheckoutSeamlessParametersTrait;

    /**
     * The common name for this gateway driver API.
     */
    public function getName()
    {
        return 'Wirecard Checkout Seamless Client';
    }

    /**
     * 
     */
    public function getDefaultParameters()
    {
        $params = parent::getDefaultParameters();

        return $params;
    }

    /**
     * The authorization transaction.
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutSeamlessAuthorizeRequest', $parameters);
    }

    /**
     * The purchase transaction.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutSeamlessPurchaseRequest', $parameters);
    }

    /**
     * Initialise secure data storage for a new Checkout Seamless transaction.
     */
    public function storageInit(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutSeamlessStorageInitRequest', $parameters);
    }

    /**
     * The capture transaction.
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\BackendSeamlessCaptureRequest', $parameters);
    }

    /**
     * The void transaction.
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\BackendSeamlessVoidRequest', $parameters);
    }

    /**
     * The refund transaction.
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\BackendSeamlessRefundRequest', $parameters);
    }

    /**
     * Accept an incoming notification (a ServerRequest).
     */
    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\NotificationServer', $parameters);
    }

    /**
     * Create a new order number in advance of assigning it to a new transaction.
     */
    public function createOrderNumber(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\BackendSeamlessOrderNumberRequest', $parameters);
    }

    /**
     * Get the financial institutions for a payment type.
     */
    public function getFinancialInstitutions(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\BackendSeamlessFinancialInstitutionsRequest', $parameters);
    }
}
