<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Seamless Capture Request.
 */

class BackendSeamlessCaptureRequest extends BackendPageCaptureRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/deposit';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
