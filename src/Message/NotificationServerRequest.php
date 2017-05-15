<?php

namespace Omnipay\Wirecard\Message;

/**
 * Accept server request notifications from Wirecard.
 * This is named the "confirm" response in Wirecard documentation.
 */

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Common\Message\ResponseInterface as OmnipayResponseInterface;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\NotificationInterface;

class NotificationServerRequest extends OmnipayAbstractRequest implements NotificationInterface, OmnipayResponseInterface
{
    // For checking the fingerprint.
    use HasFingerprintTrait;

    // Helper functions for accessing the data values
    use HasDataTrait;

    // Payment states can be found on AbstractResponse::PAYMENT_STATE_*

    public function getData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        $data = $this->httpRequest->request->all();

        return $data;
    }

    /**
     * TBC
     */
    public function getMessage()
    {
        return null;
    }

    /**
     * Translate the Wirecard status values to OmniPay status values.
     */
    public function getTransactionStatus()
    {
        // If the fingerprint is invalid, then the result cannot be
        // trusted, so just fail it.
        // TODO: we probably need a similar check when fetching the message.

        if (! $this->isValid()) {
            return static::STATUS_FAILED;
        }

        switch ($this->getDataValue('paymentState')) {
            case AbstractResponse::PAYMENT_STATE_SUCCESS:
                return static::STATUS_COMPLETED;

            case AbstractResponse::PAYMENT_STATE_CANCEL:
                // FIXME (in Omnipay Common): This is really a missing status in the interface.
                return static::STATUS_FAILED;

            case AbstractResponse::PAYMENT_STATE_PENDING:
                return static::STATUS_PENDING;

            case AbstractResponse::PAYMENT_STATE_FAILURE:
            default:
                return static::STATUS_FAILED;
        }
    }

    /**
     * TBC
     */
    public function sendData($data)
    {
        return $this;
    }

    /**
     * We put the transaction ID into a custom field, which will be passed
     * through by the gateway to the notification data.
     */
    public function getTransactionId()
    {
        return $this->getDataValue(AbstractCheckoutPurchaseRequest::CUSTOM_FIELD_NAME_TRANSACTION_ID);
    }

    public function getPaymentState()
    {
        return $this->getDataValue('paymentState');
    }

    public function getRequest()
    {
        return $this;
    }

    public function isRedirect()
    {
        return false;
    }

    /**
     * 
     */
    public function isSuccessful()
    {
        if ($this->getDataValue('fingerprintIsValid') !== true) {
            return false;
        }

        $paymentState = $this->getPaymentState();

        return (
            $paymentState === static::PAYMENT_STATE_SUCCESS
            || $paymentState === static::PAYMENT_STATE_PENDING
        );
    }

    /**
     * Is the transaction cancelled by the user?
     *
     * @inherit
     */
    public function isCancelled()
    {
        return ($this->getPaymentState() === AbstractResponse::PAYMENT_STATE_CANCEL);
    }

    /**
     * There are no codes for Wirecard Checkout Page.
     */
    public function getCode()
    {
        return null;
    }
}
