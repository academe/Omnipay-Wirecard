<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Capture Request.
 */

//use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

class BackendCaptureRequest extends AbstractBackendRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'deposit';

    protected $endpoint = 'https://checkout.wirecard.com/page/toolkit.php';

    /**
     * Collect the data together to send to the Gateway.
     */
    public function getData()
    {
        $data = $this->getBaseData();

        // Fields mandatory for the deposit command.

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

        // The fingerprint is calculated with the secret inserted as the element immediately
        // after the toolkitPassword, and that will depend on whether the shopId is supplied.
        // FIXME: to be less complicated, put the secret in the data then remove it later.

        $secret_position = ($this->getShopId() ? 3 : 2);

        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        // Remove the sectet now we have the fingerprint
        unset($data['secret']);

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
