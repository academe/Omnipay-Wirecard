<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Seamless Fetch Transaction Request.
 */

class BackendSeamlessFetchTransactionRequest extends BackendPageFetchTransactionRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/getOrderDetails';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}


