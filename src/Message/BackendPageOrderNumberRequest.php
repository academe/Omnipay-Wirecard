<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Page Generate Order Number Request.
 */

class BackendPageOrderNumberRequest extends AbstractBackendRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'generateOrderNumber';

    /**
     * Collect the data together to send to the Gateway.
     */
    public function getData()
    {
        $data = $this->getBaseData();

        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        // Remove the sectet now we have the fingerprint
        unset($data['secret']);

        return $data;
    }
}
