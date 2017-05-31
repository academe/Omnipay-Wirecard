<?php

namespace Omnipay\Wirecard\Traits;

/**
 * Manage parameters shared betweem the gateway and the message levels.
 * These are parameters specific to Checkout Page only.
 */

use Omnipay\Common\Exception\InvalidRequestException;

trait CheckoutPageParametersTrait
{
    /**
     * Text displayed to your consumer during the payment process. 
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
     * URL of your online shop where your online shop logo is located.
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
     * Hex-coded RGB color-value as background color for the brand image
     * containing the credit card logos, e.g. "f0f0f0".
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
}
