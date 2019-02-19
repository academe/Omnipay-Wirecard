<?php

namespace Omnipay\Wirecard\Message\Checkout\Page;

/**
 * Complete a Wirecard Checkout Page purchase transaction on the
 * user returning to the merchant shop.
 * Experimentally, this one class covers both the request and the response,
 * since not further requests back to the gateway are needed.
 * The advantage of doing this is that all the results needed are in the
 * initial request object. A merchant site can still send() that message
 * and get the same message back.
 * It should be possible to extend this as the notification handler too. If
 * so, then the HasFingerprintTrait becomes redundant.
 */

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\Wirecard\Message\Checkout\AbstractRequest;
use Omnipay\Wirecard\Message\AbstractResponse;
use Omnipay\Wirecard\Message\HasDataTrait;
use Omnipay\Wirecard\Message\HandlesNotificationTrait;

class CompleteResponse extends OmnipayAbstractResponse
{
    use HasDataTrait;
    use HandlesNotificationTrait;

    protected $transactionIdCheckEnabled = true;

    protected $originalTransactionId;
    protected $secret;

    public function isRedirect()
    {
        return false;
    }

    /**
     * The secret for hashing.
     */
    public function setSecret($value)
    {
        return $this->secret = $value;
    }

    public function getSecret()
    {
        return $this->getRequest()->getSecret()
            ?? $this->secret;
    }

    /**
     * The original transactionId that we are expecting to get back.
     */
    public function setOriginalTransactionId($value)
    {
        $this->originalTransactionId = $value;
        return $this;
    }

    public function getOriginalTransactionId()
    {
        return $this->getRequest()->getTransactionId()
            ?? $this->originalTransactionId;
    }

    /**
     * @inherit
     */
    public function isExpectedTransactionId()
    {
        return $this->getTransactionId()
            && $this->getTransactionId() === $this->getOriginalTransactionId();
    }
}
