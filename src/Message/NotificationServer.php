<?php

namespace Omnipay\Wirecard\Message;

/**
 * Accept server request notifications from Wirecard.
 * This is named the "confirm" response in Wirecard documentation.
 *
 * This handles just Wirecard Checkout Page notifications for the moment.
 * Depending on the structure and signing of notifications from other
 * Wirecard API modes, this handler may be expanded, or may need to be
 * split into separate handlers.
 *
 * The notification route does not need to return any particular result
 * data, e.g. nothing to say that it accepts the data or not. This means
 * the notification is almost identical to the complete* methods that
 * accept the signed results at the front end.
 */

use Omnipay\Common\Message\NotificationInterface;

class NotificationServer extends CheckoutPageComplete implements NotificationInterface
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
