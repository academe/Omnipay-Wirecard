<?php

namespace Omnipay\Wirecard\Message\Backend\Page;

/**
 * Wirecard Page Capture Request.
 */

use Omnipay\Wirecard\Message\Backend\AbstractRequest;

class CaptureRequest extends AbstractRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'deposit';

    /**
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        $data = [];

        $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();

        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();

        // Fields optional for the deposit command.

        if ($this->getMerchantReference()) {
            $data['merchantReference'] = $this->getMerchantReference();
        }

        if ($this->getCustomerStatement()) {
            $data['customerStatement'] = $this->getCustomerStatement();
        }

        // Shopping basket items (with an extended basket for additional fields).

        if ($items = $this->getItems()) {
            $data = array_merge($data, $this->itemsAsArray($items));
        }

        return $data;
    }

    /**
     * Get the merchant reference.
     *
     * @return string
     */
    public function getMerchantReference()
    {
        return $this->getParameter('merchantReference');
    }

    /**
     * Sets merchant reference.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setMerchantReference($value)
    {
        return $this->setParameter('merchantReference', $value);
    }
}
