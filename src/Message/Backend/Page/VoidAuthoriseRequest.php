<?php

namespace Omnipay\Wirecard\Message\Backend\Page;

/**
 * Wirecard Void Approval Request.
 * Cancel an authorised payment completely.
 */

use Omnipay\Wirecard\Message\Backend\AbstractRequest;

class VoidAuthoriseRequest extends AbstractRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'approveReversal';

    /**
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        $data = [];

        $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();

        return $data;
    }
}
