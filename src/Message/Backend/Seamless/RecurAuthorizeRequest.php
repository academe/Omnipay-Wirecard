<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Recur Authorize Request.
 */

use Omnipay\Wirecard\Message\Backend\Page\RecurPurchaseRequest as PageRecurPurchaseRequest;

class RecurAuthorizeRequest extends RecurPurchaseRequest
{
    /**
     * Turns off end-of day payment capture.
     */
    protected $autoDeposit = 'no';
}
