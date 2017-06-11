<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Seamless Void Request.
 */

class BackendSeamlessVoidRequest extends BackendPageVoidRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/depositReversal';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
