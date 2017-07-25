<?php

namespace Omnipay\Wirecard;

/**
 * Wirecard Checkout Page driver for Omnipay
 */

use Omnipay\Wirecard\Message\Checkout\Page\ParametersTrait as PageParametersTrait;
use Omnipay\Wirecard\Message\Checkout\ParametersTrait;
use Omnipay\Common\Exception\InvalidRequestException;

class CheckoutPageGateway extends AbstractGateway
{
    // Custom parameters implemented for the Checkout APIs.
    use ParametersTrait;

    // Custom parameters implemented for the Checkout Page API.
    use PageParametersTrait;

    /**
     * The common name for this gateway driver API.
     */
    public function getName()
    {
        return 'Wirecard Checkout Page Client';
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
            '\Omnipay\Wirecard\Message\Checkout\Page\AuthorizeRequest',
            $parameters
        );
    }

    /**
     * The recur authorize transaction.
     */
    public function recurAuthorize(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Page\RecurAuthorizeRequest',
            $parameters
        );
    }

    /**
     * The purchase transaction.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Checkout\Page\PurchaseRequest',
            $parameters
        );
    }

    /**
     * The recur purchase transaction.
     */
    public function recurPurchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Page\RecurPurchaseRequest',
            $parameters
        );
    }

    /**
     * The complete authorization transaction (capturing data retuned with the user).
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Checkout\Page\Complete',
            $parameters
        );
    }

    /**
     * The complete purchase transaction (capturing data retuned with the user).
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Checkout\Page\Complete',
            $parameters
        );
    }

    /**
     * The capture transaction.
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Page\CaptureRequest',
            $parameters
        );
    }

    /**
     * The void capture transaction.
     */
    public function voidCapture(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Page\VoidCaptureRequest',
            $parameters
        );
    }

    /**
     * The void authorization transaction.
     */
    public function voidAuthorize(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Page\VoidAuthorizeRequest',
            $parameters
        );
    }

    /**
     * The refund transaction.
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Page\RefundRequest',
            $parameters
        );
    }

    /**
     * The void refund transaction.
     */
    public function voidRefund(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Page\VoidRefundRequest',
            $parameters
        );
    }

    /**
     * The void transaction.
     * This onlt voids the capture request right now. It should
     * probably void the authorisation too, after checking the state
     * of the transaction.
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
            '\Omnipay\Wirecard\Message\Backend\Page\OrderNumberRequest',
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
            '\Omnipay\Wirecard\Message\Backend\Page\FinancialInstitutionsRequest',
            $parameters
        );
    }

    /**
     * Fetch details for a transaction.
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\Wirecard\Message\Backend\Page\FetchTransactionRequest',
            $parameters
        );
    }
}
