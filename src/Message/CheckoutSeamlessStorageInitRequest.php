<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Checkout Page Purchase.
 * A single payment method must be chosen.
 * Only a subset og payment methods require teh data storage to be initialised.
 */

use Omnipay\Wirecard\Traits\CheckoutSeamlessParametersTrait;

class CheckoutSeamlessStorageInitRequest extends AbstractRequest
{
    // Custom parameters implemented for the Checkout Seamless API.
    use CheckoutSeamlessParametersTrait;

    // The data storage initialisation endpoi8nt URL.
    protected $endpoint = 'https://checkout.wirecard.com/seamless/dataStorage/init';

    protected function createResponse($data)
    {
        return $this->response = new CheckoutSeamlessStorageInitResponse($this, $data);
    }

    /**
     * Checks if the payment method is one that requires a secure data
     * storage to be initialised.
     *
     * @return bool True if data storage needs to be initialised.
     */
    protected function dataStorageRequired()
    {
        $paytment_type = $this->getPaymentMethod();

        $sensitive_details = [
            static::PAYMENT_TYPE_CCARD,
            static::PAYMENT_TYPE_CCARD_MOTO,
            static::PAYMENT_TYPE_MAESTRO,
            static::PAYMENT_TYPE_SEPA_DD,
            static::PAYMENT_TYPE_PBX,
            static::PAYMENT_TYPE_GIROPAY,
            static::PAYMENT_TYPE_VOUCHER,
        ];

        return (in_array($paytment_type, $sensitive_details));
    }

    /**
     * Depending on the payment type, a data storage may or may not need to be
     * intialised on the gateway, involving a remote call.
     */
    public function sendData($data)
    {
        if ($this->dataStorageRequired()) {
            $storage_data = $this->sendHttp($data);
        } else {
            $storage_data = [];
        }

        $storage_data['paymentMethod'] = $this->getPaymentMethod();

        return $this->createResponse($storage_data);
    }

    /**
     * Construct the request data to send to the gateway to get
     * the secure data storage token.
     *
     * @return array
     */
    public function getData()
    {
        $data= [];

        // The order is important for the fingerprint.
        $data['customerId'] = $this->getCustomerId();
        $data['shopId'] = $this->getShopId();
        $data['orderIdent'] = $this->getTransactionId();
        $data['returnUrl'] = $this->getReturnUrl();
        $data['language'] = $this->getLanguage();
        $data['javascriptScriptVersion'] = $this->getJavascriptScriptVersion();

        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        return $data;
    }
}
