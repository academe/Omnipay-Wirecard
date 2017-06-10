<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Refund Request.
 */

class BackendPageRefundRequest extends BackendPageCaptureRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'refund';
}
