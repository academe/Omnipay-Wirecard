<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Checkout Page Purchase.
 * A single payment method must be chosen.
 * Only a subset og payment methods require teh data storage to be initialised.
 */

use Omnipay\Wirecard\Traits\CheckoutPageParametersTrait;

class CheckoutSeamlessPurchaseRequest extends AbstractRequest
{
    // The data storage initialisation endpoi8nt URL.
    protected $endpoint = 'https://checkout.wirecard.com/seamless/dataStorage/init';

    protected function createResponse($data)
    {
        return $data; // Temporary for testing.
        return $this->response = new CheckoutSeamlessPurchaseResponse($this, $data);
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
            $headers = [];
            $httpResponse = $this->httpClient->post($this->getEndpoint(), $headers, $data)->send();

            // The response is a query string.
            // Parse it into an array.
            parse_str((string)$httpResponse->getBody(), $response_data);
        } else {
            $response_data = [];
        }

        return $this->createResponse($response_data);

    }

    /**
     * Construct the request data to send.
     *
     * @return array
     */
    public function getData()
    {
        $data= [];

        // TODO: the order is important for the fingerprint.
        $data['customerId'] = $this->getCustomerId();
        $data['language'] = $this->getLanguage();
        $data['returnUrl'] = $this->getReturnUrl();

        // TODO: orderIdent
        // TODO: fingerprint
        // TODO: response object to handle [multiple] error messages

        return $data;
    }
}

