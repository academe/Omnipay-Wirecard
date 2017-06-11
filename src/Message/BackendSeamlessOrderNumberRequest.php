<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Seamless Create Order Number Request.
 */

class BackendSeamlessOrderNumberRequest extends BackendPageOrderNumberRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/generateOrderNumber';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
