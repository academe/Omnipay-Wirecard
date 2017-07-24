<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Create Order Number Request.
 */

use Omnipay\Wirecard\Message\Backend\Page\OrderNumberRequest as PageOrderNumberRequest;

class OrderNumberRequest extends PageOrderNumberRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/generateOrderNumber';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
