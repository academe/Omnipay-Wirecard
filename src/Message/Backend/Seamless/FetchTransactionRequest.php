<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Fetch Transaction Request.
 */

use Omnipay\Wirecard\Message\Backend\Page\FetchTransactionRequest as PageFetchTransactionRequest;

class FetchTransactionRequest extends PageFetchTransactionRequest
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
