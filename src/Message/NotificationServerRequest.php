<?php

namespace Omnipay\Wirecard\Message;

/**
 * Accept server request notifications from Wirecard.
 * This is named the "confirm" response in Wirecard documentation.
 */

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\NotificationInterface;

class NotificationServerRequest extends OmnipayAbstractRequest implements NotificationInterface
{
    // For checking the fingerprint.
    use HasFingerprintTrait;

    /**
     * Get a single data value from the ServerRequest data.
     */
    protected function getValue($name, $default = null)
    {
        $data = $this->getData();
        $value = array_key_exists($name, $data) ? $data[$name] : $default;

        return $value;
    }

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
     * Checks the fingerprint of the data is valid.
     *
     * @return bool True if the filngerprint is found and is valid.
     */
    public function isValid()
    {
        return $this->checkFingerprint($this->getData());
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

        switch ($this->getValue('paymentState')) {
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

    public function sendData($data)
    {
        return $this->createResponse($data);
    }

    /**
     * We put the transaction ID into a custom field, which will be passed
     * through by the gateway to the notification data.
     */
    public function getTransactionId()
    {
        return $this->getValue(AbstractCheckoutPurchaseRequest::CUSTOM_FIELD_NAME_TRANSACTION_ID);
    }

    public function getPaymentState()
    {
        return $this->getValue('paymentState');
    }
}
