<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Void Approval Request.
 * Cancel an authorised payment completely.
 */

class BackendPageVoidAuthoriseRequest extends AbstractBackendRequest
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
