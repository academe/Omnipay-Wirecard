<?php

namespace Omnipay\Wirecard\Message\Backend\Page;

/**
 * Wirecard Refund Request.
 */

class RefundRequest extends CaptureRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'refund';
}
