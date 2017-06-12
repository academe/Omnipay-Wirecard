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
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        $data = [];

        return $data;
    }
}
