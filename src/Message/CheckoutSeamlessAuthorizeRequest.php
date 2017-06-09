<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Checkout Seamless Authorize.
 */

class CheckoutSeamlessAuthorizeRequest extends CheckoutSeamlessPurchaseRequest
{
    /**
     * Disable the automatic capture at the end of the day.
     */
    protected $autoDeposit = 'no';
}
