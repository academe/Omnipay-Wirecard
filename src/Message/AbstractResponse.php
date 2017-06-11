<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Abstract Request.
 */

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\Common\Exception\InvalidRequestException;

abstract class AbstractResponse extends OmnipayAbstractResponse
{
    const PAYMENT_STATE_SUCCESS = 'SUCCESS';
    const PAYMENT_STATE_CANCEL  = 'CANCEL';
    const PAYMENT_STATE_FAILURE = 'FAILURE';
    const PAYMENT_STATE_PENDING = 'PENDING';
}
