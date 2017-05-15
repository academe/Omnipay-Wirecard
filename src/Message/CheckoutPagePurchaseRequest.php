<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Checkout Page Purchase.
 */

class CheckoutPagePurchaseRequest extends AbstractCheckoutPurchaseRequest
{
    protected function createResponse($data)
    {
        return $this->response = new CheckoutPagePurchaseResponse($this, $data);
    }

    /**
     * 
     */
    public function setDisplayText($value)
    {
        return $this->setParameter('displayText', $value);
    }

    public function getDisplayText()
    {
        return $this->getParameter('displayText');
    }

    /**
     * 
     */
    public function setImageUrl($value)
    {
        return $this->setParameter('imageUrl', $value);
    }

    public function getImageUrl()
    {
        return $this->getParameter('imageUrl');
    }

    /**
     * 
     */
    public function setBackgroundColor($value)
    {
        return $this->setParameter('backgroundColor', $value);
    }

    public function getBackgroundColor()
    {
        return $this->getParameter('backgroundColor');
    }

    /**
     * 
     */
    public function setMaxRetries($value)
    {
        return $this->setParameter('maxRetries', $value);
    }

    public function getMaxRetries()
    {
        return $this->getParameter('maxRetries');
    }

    /**
     * 
     */
    public function setPaymenttypeSortOrder($value)
    {
        return $this->setParameter('paymenttypeSortOrder', $value);
    }

    public function getPaymenttypeSortOrder()
    {
        return $this->getParameter('paymenttypeSortOrder');
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

        // The fingerprint is calculated at the end.
        // The fingerprint order field is included in both the order list and the fingerprint.

        $data['requestFingerprintOrder'] = $this->getRequestFingerprintOrder($data);
        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        if ($this->getMaxRetries()) {
            $data['maxRetries'] = $this->getMaxRetries();
        }

        if ($this->getPaymenttypeSortOrder()) {
            $data['paymenttypeSortOrder'] = $this->getPaymenttypeSortOrder();
        }

        return $data;
    }
}
