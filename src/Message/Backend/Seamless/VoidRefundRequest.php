<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Void Refund Request.
 */

use Omnipay\Wirecard\Message\Backend\Page\VoidRefundRequest as PageVoidRefundRequest;

class VoidRefundRequest extends PageVoidRefundRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/refundReversal';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
