<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Seamless Void Authorise Request.
 * Cancel an authorised payment completely.
 */

class BackendSeamlessVoidAuthorizeRequest extends BackendPageVoidAuthorizeRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/approveReversal';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
