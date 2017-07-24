<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Void Request.
 */

use Omnipay\Wirecard\Message\Backend\Page\VoidCaptureRequest as PageVoidCaptureRequest;

class VoidCaptureRequest extends PageVoidCaptureRequest
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
