<?php

namespace Omnipay\Wirecard\Message;

/**
 * Checkout Seemless Purchase Response.
 */

//use Omnipay\Common\Message\RedirectResponseInterface;

class CheckoutSeamlessPurchaseResponse extends AbstractSeamlessResponse
{
    public function isSuccessful()
    {
        return true;
    }
}
