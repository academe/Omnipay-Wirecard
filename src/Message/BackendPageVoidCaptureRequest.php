<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Void Request.
 * This cancels a deposit (i.e. capture) request. The capture will
 * happen at day end, so must be performed on the day the transaction
 * was set to capture (before midnight).
 * Note that this does not cancel the authorisation, which can remain
 * for 7-14 days, so this order could in theory still be captured at a
 * later date.
 * To cancel the authorisation, a vodAuthorize (approveReversal) must
 * be performed. It *may* be useful to automatically do both for Omnipay
 * so authorisations do not hang around against an end user's card for days?
 */

class BackendPageVoidCaptureRequest extends AbstractBackendRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'depositReversal';

    /**
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        $data = [];

        $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();
        $data['paymentNumber'] = $this->getPaymentNumber();

        return $data;
    }

    /**
     * Get the payment number.
     *
     * @return string
     */
    public function getPaymentNumber()
    {
        return $this->getParameter('paymentNumber');
    }

    /**
     * Sets payment number.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setPaymentNumber($value)
    {
        return $this->setParameter('paymentNumber', $value);
    }
}
