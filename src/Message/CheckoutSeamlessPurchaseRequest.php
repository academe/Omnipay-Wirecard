<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Checkout Seamless Purchase.
 */

use Omnipay\Wirecard\Traits\CheckoutSeamlessParametersTrait;

class CheckoutSeamlessPurchaseRequest extends AbstractCheckoutRequest
{
    // Custom parameters implemented for the Checkout Seamless API.
    use CheckoutSeamlessParametersTrait;

    /**
     *
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/frontend/init';

    /**
     * Automatically capture the authorised amount at the end of the day.
     * This makes the transaction a purchase rather than an authorisation.
     */
    protected $autoDeposit = 'yes';

    /**
     * Collect the data to send to the gateway.
     */
    /**
     * Construct the request data to send.
     *
     * @return array
     */
    public function getData()
    {
        $data = $this->getBaseData();

        $data['confirmUrl'] = $this->getNotifyUrl();

        $data['consumerIpAddress'] = $this->getClientIp();

        $data['consumerUserAgent'] = $this->getConsumerUserAgent();

        // Whether to auto-capture the authorised amount at end of the day.
        $data['autoDeposit'] = $this->autoDeposit;

        // If you do not pass in the storageId, then on the demo gateway at least the
        // user will be redirected to a remotre hosted form to enter the details.
        // It looks like the form can be used in an iframe.
        if ($this->getStorageId()) {
            $data['storageId'] = $this->getStorageId();
        }

        //if ($this->getOrderIdent()) {
            $data['orderIdent'] = $this->getTransactionId();
        //}

        // The fingerprint is calculated at the end.
        // The fingerprint order field is included in both the order list and the fingerprint.

        $data['requestFingerprintOrder'] = $this->getRequestFingerprintOrder($data);
        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        return $data;
    }

    /**
     * Send teh request to the remote gateway.
     */
    public function sendData($data)
    {
        return $this->createResponse(
            $this->sendHttp($data)
        );
    }

    /**
     * Create a response message object, given the response data from the gateway.
     */
    protected function createResponse($data)
    {
        return $this->response = new CheckoutSeamlessPurchaseResponse($this, $data);
    }
}
