<?php

namespace Omnipay\Wirecard;

/**
 * Wirecard Checkout Seamless driver for Omnipay
 */

use Omnipay\Wirecard\Message\Checkout\Seamless\ParametersTrait as SeamlessParametersTrait;
use Omnipay\Wirecard\Message\Checkout\ParametersTrait;
use Omnipay\Common\Exception\InvalidRequestException;

class CheckoutSeamlessGateway extends AbstractGateway
{
    // Custom parameters implemented for the Checkout APIs.
    use ParametersTrait;

    // Custom parameters implemented for the Checkout Seamless API.
    use SeamlessParametersTrait;

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
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Checkout\Seamless\AuthorizeRequest',
            $parameters
        );
    }

    /**
     * The purchase transaction.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Checkout\Seamless\PurchaseRequest',
            $parameters
        );
    }

    /**
     * The recur purchase transaction.
     */
    public function recurPurchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Seamless\RecurPurchaseRequest',
            $parameters
        );
    }

    /**
     * Initialise secure data storage for a new Checkout Seamless transaction.
     */
    public function storageInit(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Checkout\Seamless\StorageInitRequest',
            $parameters
        );
    }

    /**
     * The capture transaction.
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Seamless\CaptureRequest',
            $parameters
        );
    }

    /**
     * The void capture transaction.
     */
    public function voidCapture(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Seamless\VoidCaptureRequest',
            $parameters
        );
    }

    /**
     * The void authorization transaction.
     */
    public function voidAuthorize(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Seamless\VoidAuthorizeRequest',
            $parameters
        );
    }

    /**
     * The refund transaction (creates a credit note).
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Seamless\RefundRequest',
            $parameters
        );
    }

    /**
     * The void refund (i.e. a credit note) transaction.
     */
    public function voidRefund(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Seamless\VoidRefundRequest',
            $parameters
        );
    }

    /**
     * The void transaction.
     * Only voids the capture request at this time, leaving any authorisaion
     * still active, until it expires (7-14 days) or is voided by voidAuthorize.
     */
    public function void(array $parameters = array())
    {
        return $this->voidCapture($parameters);
    }

    /**
     * Accept an incoming notification (a ServerRequest).
     */
    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\NotificationServer',
            $parameters
        );
    }

    /**
     * Create a new order number in advance of assigning it to a new transaction.
     */
    public function createOrderNumber(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Seamless\OrderNumberRequest',
            $parameters
        );
    }

    /**
     * An alias to createOrderNumber in more "Omnipay" parlance.
     */
    public function createTransactionReference(array $parameters = array())
    {
        return $this->createOrderNumber($parameters);
    }

    /**
     * Get the financial institutions for a payment type.
     */
    public function getFinancialInstitutions(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Seamless\FinancialInstitutionsRequest',
            $parameters
        );
    }

    /**
     * Fetch details for a transaction.
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\Backend\Seamless\FetchTransactionRequest', $parameters);
    }
}
