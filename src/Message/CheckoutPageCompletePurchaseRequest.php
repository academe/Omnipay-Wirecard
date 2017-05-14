<?php

namespace Omnipay\Wirecard\Message;

/**
 * Complete a Wirecard Checkout Page purchase transaction on the
 * user returning to the merchant shop.
 * This will never redirect (TO CHECK) because the user has already
 * performed all off-site credentials - including 3D Secure, a visit to 
 * PayPal etc.
 */

class CheckoutPageCompletePurchaseRequest extends AbstractRequest
{
    // The results will be signed, se we need to be able to
    // validate the fingerprint.
    use HasFingerprintTrait;

    /**
     * Checks the fingerprint of the data is valid.
     *
     * @return bool True if the filngerprint is found and is valid.
     */
    public function isValid()
    {
        return $this->checkFingerprint($this->getData());
    }

    /**
     * The transaction result will be sent through POST parameters.
     */
    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    /**
     * Simple pass-through to the response object.
     */
    public function sendData($data)
    {
        return $this->response = new CheckoutPageCompletePurchaseResponse($this, $data);
    }
}
