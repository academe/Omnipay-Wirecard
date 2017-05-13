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
        $data = $this->getData();

        // The fingerprint order list and the fingerprint value must be present.
        if (! array_key_exists('responseFingerprintOrder', $data) || ! array_key_exists('responseFingerprint', $data)) {
            // No fingerprint to check.
            return false;
        }

        // The order will be a list of fields with the secret added.
        // Note that the secret could be anywhere in the value string
        // to hash, and not just at the end as in all the examples.

        $fields = explode(',', $data['responseFingerprintOrder']);

        $hash_string = '';

        foreach($fields as $field) {
            if ($field === 'secret') {
                // Append the secret to the hash string.

                $hash_string .= $this->getSecret();
            } elseif (! array_key_exists($field, $data)) {
                // A field listed as being in the fingerprint was not sent.
                // Even if it is empty, we have to see it being sent.
                // FIXME: the example application just defaults a missing field
                // to an empty string. We should probably do that.

                return false;
            } else {
                // Append the field value to the hash string.

                $hash_string .= $data[$field];
            }
        }

        $fingerprint = hash_hmac('sha512', $hash_string, $this->getSecret());

        return ($fingerprint === $data['responseFingerprint']);
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
