<?php

namespace Omnipay\Wirecard\Message\Backend\Page;

/**
 * Create a new authorisation from an existing authorised order.
 */

class RecurAuthorizeRequest extends RecurPurchaseRequest
{
    /**
     * Turns off end-of day payment capture.
     */
    protected $autoDeposit = 'no';
}
