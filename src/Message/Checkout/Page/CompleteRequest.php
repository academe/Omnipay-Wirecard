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

use Omnipay\Common\Message\ResponseInterface as OmnipayResponseInterface;
use Omnipay\Wirecard\Message\Checkout\AbstractRequest;
use Omnipay\Wirecard\Message\AbstractResponse;
use Omnipay\Wirecard\Message\HasDataTrait;

class CompleteRequest extends AbstractRequest
{
    use HasDataTrait;

    /**
     * @inheric
     */
    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    /**
     * Create a new Response message given the raw data in the response.
     */
    protected function createResponse($data)
    {
        $this->validate('secret', 'transactionId');

        $this->response = new CompleteResponse($this, $this->getData());

        // Set the original transactionId and the secret, both for
        // validating the response.

        $this->response->setOriginalTransactionId($this->getTransactionId());
        $this->response->setSecret($this->getSecret());

        return $this->response;
    }

    /**
     * The secret for hashing.
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * The transaction ID supplied by the gateway, in case the
     * application needs to look it up before calling send().
     */
    public function getOriginalTransactionId()
    {
        return $this->getDataValue(AbstractRequest::CUSTOM_FIELD_NAME_TRANSACTION_ID);
    }
}
