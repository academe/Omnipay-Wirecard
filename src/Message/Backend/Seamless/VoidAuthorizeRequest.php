<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Void Authorise Request.
 * Cancel an authorised payment completely.
 */

use Omnipay\Wirecard\Message\Backend\Page\VoidAuthorizeRequest as PageVoidAuthorizeRequest;

class VoidAuthorizeRequest extends PageVoidAuthorizeRequest
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
