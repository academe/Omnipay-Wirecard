<?php

namespace Omnipay\Wirecard\Message\Backend\Page;

/**
 * Wirecard Page Generate Order Number Request.
 */

use Omnipay\Wirecard\Message\Backend\AbstractRequest;

class OrderNumberRequest extends AbstractRequest
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
