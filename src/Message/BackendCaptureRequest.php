<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Capture Request.
 */

//use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

class BackendCaptureRequest extends AbstractRequest
{
    /**
     * The backend command to send.
     */
    private $command = 'deposit';

    /**
     * Collect the data together to send to the Gateway.
     */
    public function getData()
    {
        $data = [
            'customerId' => $this->getCustomerId(),
            'toolkitPassword' => $this->getToolkitPassword(),
            'secret' => $this->getSecret(),
            'command' => $this->command,
            'language' => $this->getLanguage(),
        ];

        $data['orderNumber'] = $this->getOrderNumber();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();

        // The fingerprint applies only to the mandatory parameters.
        $data['requestFingerprint'] = $this->getRequestFingerprint($data);
        // Take out the secret now we've used it.
        unset($data['secret']);
        //var_dump($data);

// The order of the fields for the signature. Note teh secret comes after the toolkit password.
/*
$customerId, $toolkitPassword,
$secret, $command,
$language, $orderNumber,
$amount, $currency,
// For each optional basket item:
$basketItems, $basketItem1ArticleNumber,
$basketItem1Quantity, $basketItem1Description,
$basketItem1Name, $basketItem1UnitGrossAmount,
$basketItem1UnitNetAmount, $basketItem1UnitTaxAmount,
$basketItem1UnitTaxRate
*/

        $data['shopId'] = $this->getShopId();

        if ($this->getCustomerStatement()) {
            $data['customerStatement'] = $this->getCustomerStatement();
        }

        // TODO Optional: merchantReference, basket.

        return $data;
    }

    // TODO: Move this more central, operate it from properties, and allow an override.
    public function getEndpoint()
    {
        return 'https://checkout.wirecard.com/page/toolkit.php';
    }

    /**
     * The response body will be a list of name=value pairs.
     */
    protected function createResponse($data)
    {
        return $this->response = new BackendResponse($this, $data);
    }

    /**
     * Data is sent application/x-www-form-urlencoded
     */
    public function sendData($data)
    {
        $headers = [];
        $httpResponse = $this->httpClient->post($this->getEndpoint(), $headers, $data)->send();

        // The response is a query string.
        // Parse it into an array.
        parse_str((string)$httpResponse->getBody(), $response_data);

        return $this->createResponse($response_data);
    }
}
