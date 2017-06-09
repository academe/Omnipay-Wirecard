<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Checkout Page Purchase.
 */

use Omnipay\Wirecard\Traits\CheckoutPageParametersTrait;

class CheckoutPagePurchaseRequest extends AbstractCheckoutRequest
{
    // Custom parameters implemented for the Checkout Page API.
    use CheckoutPageParametersTrait;

    /**
     * Automatically capture the authorised amount at the end of the day.
     * This makes the transaction a purchase rather than an authorisation.
     */
    protected $autoDeposit = 'yes';

    /**
     * @inherit
     */
    protected function createResponse($data)
    {
        return $this->response = new CheckoutPageResponse($this, $data);
    }

    /**
     * Construct the request data to send.
     *
     * @return array
     */
    public function getData()
    {
        $data = $this->getBaseData();

        if ($this->getDisplayText()) {
            $data['displayText'] = $this->getDisplayText();
        }

        if ($this->getImageUrl()) {
            $data['imageUrl'] = $this->getImageUrl();
        }

        if ($this->getBackgroundColor()) {
            $data['backgroundColor'] = $this->getBackgroundColor();
        }

        // Whether to auto-capture the authorised amount at end of the day.
        $data['autoDeposit'] = $this->autoDeposit;

        if ($this->getMaxRetries()) {
            $data['maxRetries'] = $this->getMaxRetries();
        }

        if ($this->getPaymenttypeSortOrder()) {
            $data['paymenttypeSortOrder'] = $this->getPaymenttypeSortOrder();
        }

        // The fingerprint is calculated at the end.
        // The fingerprint order field is included in both the order list and the fingerprint.

        $data['requestFingerprintOrder'] = $this->getRequestFingerprintOrder($data);
        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        return $data;
    }
}
