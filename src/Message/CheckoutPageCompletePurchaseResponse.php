<?php

namespace Omnipay\Wirecard\Message;

/**
 * NOT USED - See CheckoutPageCompletePurchase instead
 *
 */

//use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\ResponseInterface as OmnipayResponseInterface;

class CheckoutPageCompletePurchaseResponse extends AbstractResponse
{
    // Helper functions for accessing the data values
    use HasDataTrait;

    // The results will be signed, se we need to be able to
    // validate the fingerprint.
    //use HasFingerprintTrait;

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
     * The raw payment state.
     *
     * @return null|string Values as listed in AbstractResponse::PAYMENT_STATE_*
     */
    public function getPaymentState()
    {
        return $this->getDataValue('paymentState');
    }

    /**
     * Translate the Wirecard status values to OmniPay status values.
     */
    public function getTransactionStatus()
    {
        // If the fingerprint is invalid, then the result cannot be
        // trusted, so just fail it.
        // TODO: we probably need a similar check when fetching the message.

        if ($this->getDataValue('fingerprintIsValid') !== true) {
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
     * Gateway Reference
     *
     * @inherit
     */
    public function getTransactionReference()
    {
        return $this->getDataValue('gatewayReferenceNumber');
    }
}
