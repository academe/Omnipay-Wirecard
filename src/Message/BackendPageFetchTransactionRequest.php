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
     * Collect the data together to send to the Gateway.
     */
    public function getData()
    {
        $data = $this->getBaseData();

        // Fields mandatory for the depositReversal (void) command.

        $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();

        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        // Remove the sectet now we have the fingerprint
        unset($data['secret']);

        return $data;
    }
}
