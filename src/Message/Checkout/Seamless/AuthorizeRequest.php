<?php

namespace Omnipay\Wirecard\Message\Checkout\Seamless;

/**
 * Wirecard Checkout Seamless Authorize.
 */

class AuthorizeRequest extends PurchaseRequest
{
    /**
     * Disable the automatic capture at the end of the day.
     */
    protected $autoDeposit = 'no';
}
