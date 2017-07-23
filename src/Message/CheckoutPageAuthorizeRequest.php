<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Checkout Page Purchase.
 */

class CheckoutPageAuthorizeRequest extends CheckoutPagePurchaseRequest
{
    /**
     * Do not automatically capture the authorised amount at the end of the day.
     * The authorisation will need to be captured before it expires some 7 to 14
     * days after authorisation.
     *
     * static::AUTO_DEPOSIT_NO
     */
    protected $autoDeposit = 'no';
}
