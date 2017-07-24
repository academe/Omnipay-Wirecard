<?php

namespace Omnipay\Wirecard\Message\Backend\Page;

/**
 * Wirecard Void Refund (credit note) Request.
 *
 * Must be performed on the same day as a refund is issued, before
 * the day-end clearing.
 */

use Omnipay\Wirecard\Message\Backend\AbstractRequest;

class VoidRefundRequest extends AbstractRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'refundReversal';

    /**
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        $data = [];

        $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();
        $data['creditNumber'] = $this->getCreditNumber();

        return $data;
    }

    /**
     * Get the credit number.
     *
     * @return string
     */
    public function getCreditNumber()
    {
        return $this->getParameter('creditNumber');
    }

    /**
     * Sets credit number.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setCreditNumber($value)
    {
        return $this->setParameter('creditNumber', $value);
    }
}
