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
     * TODO: this will be needed by the completeCheckoutPagePurchase class too,
     * so the core of it needs to go somewhere shared. It could be a static method
     * on the AbstractResponse that just checks a $data array passed in.
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
    }

    public function sendData($data)
    {
        return $this->createResponse($data);
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
}
