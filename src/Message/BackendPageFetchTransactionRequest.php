<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Fetch Transaction Request.
 */

class BackendPageFetchTransactionRequest extends AbstractBackendRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'getOrderDetails';

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
