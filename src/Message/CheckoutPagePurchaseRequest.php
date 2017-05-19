<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Checkout Page Purchase.
 */

class CheckoutPagePurchaseRequest extends AbstractCheckoutRequest
{
    /**
     * Automatically capture the authorised amount at the end of the day.
     * This makes the transaction a purchase rather than an authorisation.
     */
    protected $autoDeposit = 'yes';

    protected function createResponse($data)
    {
        return $this->response = new CheckoutPagePurchaseResponse($this, $data);
    }

    /**
     * Merchant-specified text to appear on payment page.
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
     * Merchant logo to appear on payment page.
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
     * Background colour of the payment page.
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
     * Maximum number of payment attempts for the same order.
     * This applies to a unique order number.
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
     * Sort order of payment methods and sub-methods if your consumer
     * uses SELECT as payment method.
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
