<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Refund Request.
 */

use Omnipay\Wirecard\Message\Backend\Page\RefundRequest as PageRefundRequest;

class RefundRequest extends PageRefundRequest
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
