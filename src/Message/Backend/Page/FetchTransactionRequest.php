<?php

namespace Omnipay\Wirecard\Message\Backend\Page;

/**
 * Wirecard Page Fetch Transaction Request.
 */

use Omnipay\Wirecard\Message\Backend\AbstractRequest;

class FetchTransactionRequest extends AbstractRequest
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
