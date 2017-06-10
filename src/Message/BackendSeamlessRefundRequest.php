<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Seamless Refund Request.
 */

class BackendSeamlessRefundRequest extends BackendPageRefundRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/refund';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
