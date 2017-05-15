<?php

namespace Omnipay\Wirecard\Message;

/**
 * 
 */

trait HasFingerprintTrait
{
    /**
     * Check an array of key/value pairs contains a valid fingerprint
     * using the given secret.
     *
     * @param array $data The array of field names, including the fingerprint to check.
     * @param string $secret The pre-shared secret key.
     *
     * @return bool True if the filngerprint is found and is valid.
     */
    public function checkFingerprint(array $data)
    {
        $secret = $this->getSecret();

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

                $hash_string .= $secret;
            } elseif (array_key_exists($field, $data)) {
                // Append the field value to the hash string.
                $hash_string .= $data[$field];
            }

            // If a field listed as being in the fingerprint was not sent,
            // then ignore it.
            // The official sample code just skips the field in this case,
            // defaulting it to an empty string, so we will do that.
        }

        $fingerprint = hash_hmac('sha512', $hash_string, $secret);

        return ($fingerprint === $data['responseFingerprint']);
    }

    /**
     * Return the fingerprint order string, based on the field
     * names (the keys) of the data to send.
     *
     * @param array $data The key/value data to send
     * @return string Comma-separated list of field names
     */
    public function getRequestFingerprintOrder($data)
    {
        $order = implode(',', array_keys($data));

        // Two additional fields will be included in the hash.
        $order .= ',requestFingerprintOrder,secret';

        return $order;
    }

    /**
     * Calculates the fintgerprint hash of the data to send.
     * It is assumed that the requestFingerprintOrder field has already
     * been added to this data.
     *
     * @param array $data The key/value data to send
     * @return string Fingerprint hash
     */
    function getRequestFingerprint($data)
    {
        $secret = $this->getSecret();

        $fields = implode('', array_values($data));

        // Add the secret to the string to hash, since it will
        // never be sent with the data.
        $fields .= $secret;

        return hash_hmac('sha512', $fields, $secret);
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
     * Checks the fingerprint of the incoming data is valid.
     * Note that a fingerprint is not sent with a FAILURE ro CANCEL message,
     * so those payment states are always considered valid.
     *
     * @return bool True if the filngerprint is found and is valid.
     */
    public function isValid()
    {
        $paymentState = $this->getDataValue('paymentState');

        if (
            $paymentState === AbstractResponse::PAYMENT_STATE_CANCEL
            || $paymentState === AbstractResponse::PAYMENT_STATE_FAILURE
        ) {
            return true;
        }

        return $this->checkFingerprint($this->getData());
    }

}
