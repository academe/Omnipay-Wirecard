<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Refund Request.
 */

class BackendRefundRequest extends BackendCaptureRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'refund';
}
