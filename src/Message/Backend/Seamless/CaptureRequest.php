<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Capture Request.
 */

use Omnipay\Wirecard\Message\Backend\Page\CaptureRequest as PageCaptureRequest;

class CaptureRequest extends PageCaptureRequest
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
