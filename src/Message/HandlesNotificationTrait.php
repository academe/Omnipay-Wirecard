<?php

namespace Omnipay\Wirecard\Message;

/**
 *
 */

use Omnipay\Wirecard\Message\Checkout\AbstractRequest as MessageAbstractRequest;

trait HandlesNotificationTrait
{
    /**
     * The orderNumber is a unique reference for the transaction.
     * It is used when capturing, refunding and voiding.
     * A new orderNumber can be generated before the checkout page is invoked,
     * which "reserves" it for a single time use. Ot it can be left as a surprise
     * when the transaction is successful.
     */
    public function getOrderNumber()
    {
        return $this->getDataValue('orderNumber');
    }

    /**
     * The orderNumber fits this purpose.
     */
    public function getTransactionReference()
    {
        return $this->getOrderNumber();
    }

    /**
     * The system message.
     * They do need some kind of translation. Maybe each maps from a code?
     */
    public function getMessage()
    {
        if (! $this->isValid()) {
            return 'Validation of the fingerprint failed,';
        }

        // The transactionID check is performed only for the complete* responses
        // and not for the back-end notification handler.
        // In the former case we know what we are expecting, and any deviation
        // could be a reply-hack. In the latter case, the notifications are
        // unsolicited.

        if ($this->isExpectedTransactionId()) {
            return 'Incorrect or missing transactionId';
        }

        if ($message = $this->getDataValue('message')) {
            return $message;
        }

        if ($this->isCancelled()) {
            return 'The transaction has been cancelled.';
        }

        // Successful *or* pending. Not sure if we need different messages for each.
        if ($this->isSuccessful()) {
            return 'The checkout process has been successful.';
        }
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

        if ($paymentState === AbstractResponse::PAYMENT_STATE_CANCEL
            || $paymentState === AbstractResponse::PAYMENT_STATE_FAILURE
        ) {
            return true;
        }

        return $this->checkFingerprint($this->getData());
    }

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
        if (! array_key_exists('responseFingerprintOrder', $data)
            || ! array_key_exists('responseFingerprint', $data)
        ) {
            // No fingerprint to check.
            return false;
        }

        // The fingerprint order will be a list of fields with the secret added.
        // Note that the secret could be anywhere in the value string
        // to hash, and not just at the end as in most examples.

        $fields = explode(',', $data['responseFingerprintOrder']);

        $hash_string = '';

        foreach ($fields as $field) {
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

        // Finally check the fingerprint hash against the secret.
        $fingerprint = hash_hmac('sha512', $hash_string, $secret);

        return ($fingerprint === $data['responseFingerprint']);
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        // The fingerprint must be valid to to sure it is sucessful.

        if (! $this->isValid()) {
            return false;
        }

        if (! $this->isExpectedTransactionId()) {
            return false;
        }

        $paymentState = $this->getPaymentState();

        // There are four payment states.
        // Only "SUCCESS" indicates the transaction is successful AND complete.

        return $paymentState === AbstractResponse::PAYMENT_STATE_SUCCESS;
    }

    public function getPaymentState()
    {
        return $this->getDataValue('paymentState');
    }

    public function getGatewayReferenceNumber()
    {
        return $this->getDataValue('gatewayReferenceNumber');
    }

    // The following are for credit cards.

    /**
     * Indicates whether the card holder has been successfully authenticated.
     * Returned for credit cards only.
     *
     * @return null|bool True if authenticated; False if not; Null if not applicable.
     */
    public function getAuthenticated()
    {
        $value = $this->getDataValue('authenticated');

        // The documentation states uppercase "NO" and the demo client
        // returns mixed case "No", so normalise it.
        switch (strtoupper($value)) {
            case 'YES':
                return true;
            case 'NO':
                return false;
            default:
                return;
        }
    }

    /**
     * If credit card is sucessful.
     *
     * @return string Last four digits of the credit card number
     */
    public function getAnonymousPan()
    {
        return $this->getDataValue('anonymousPan');
    }

    /**
     * If credit card is sucessful.
     *
     * @return string Credit card number with middle digits removed
     */
    public function getMaskedPan()
    {
        return $this->getDataValue('maskedPan');
    }

    /**
     * If credit card is sucessful.
     *
     * @return string Name of holder of credit card which can only be used if the acquirer supports it
     */
    public function getCardholder()
    {
        return $this->getDataValue('cardholder');
    }

    /**
     * If credit card is sucessful.
     *
     * @return string Expiry date of credit card in format “mm/yyyy”
     */
    public function getExpiry()
    {
        return $this->getDataValue('expiry');
    }

    // The following are returned for Address Verification System (AVS)

    /**
     * Alphanumeric including special characters with a variable length of up to 5 characters.
     *
     * @return string Response code of AVS check
     */
    public function getAvsResponseCode()
    {
        return $this->getDataValue('avsResponseCode');
    }

    /**
     * Alphanumeric including special characters with a variable length of up to 256 characters.
     *
     * @return string Response text of AVS check
     */
    public function getAvsResponseMessage()
    {
        return $this->getDataValue('avsResponseMessage');
    }

    /**
     * Alphanumeric including special characters with a variable length of up to 5 characters.
     *
     * @return string Provider result code of AVS check
     */
    public function getAvsProviderResultCode()
    {
        return $this->getDataValue('avsProviderResultCode');
    }

    /**
     * Alphanumeric including special characters with a variable length of up to 256 characters.
     *
     * @return string Provider result text of AVS check
     */
    public function getAvsProviderResultMessage()
    {
        return $this->getDataValue('avsProviderResultMessage');
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

    public function isPending()
    {
        return ($this->getPaymentState() === AbstractResponse::PAYMENT_STATE_PENDING);
    }

    /**
     * Unlike the backend functions, there are no codes for Wirecard Checkout Page.
     */
    public function getCode()
    {
        return null;
    }

    /**
     * We put the transaction ID into a custom field, which will be passed
     * through by the gateway to the notification data.
     */
    public function getTransactionId()
    {
        return $this->getDataValue(MessageAbstractRequest::CUSTOM_FIELD_NAME_TRANSACTION_ID);
    }

    /**
     * Checks if the expected transactionId is in the gateway request or response.
     *
     * @return bool true if transaction ID is correct, or no match is required
     */
    abstract public function isExpectedTransactionId();
}
