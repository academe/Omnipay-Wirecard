<?php

namespace Omnipay\Wirecard\Message;

/**
 * Accept server request notifications from Wirecard.
 * This is named the "confirm" response in Wirecard documentation.
 */

use Omnipay\Common\Message\NotificationInterface;

class NotificationServer extends CheckoutPageCompletePurchase implements NotificationInterface
{
    /**
     * Translate the Wirecard status values to OmniPay status values.
     */
    public function getTransactionStatus()
    {
        // If the fingerprint is invalid, then the result cannot be
        // trusted, so just fail it.

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
}
