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
    protected $command = 'deposit';

    protected $endpoint = 'https://checkout.wirecard.com/page/toolkit.php';

    /**
     * Collect the data together to send to the Gateway.
     */
    public function getData()
    {
        // The order of all fields is critical when creating the singnaturte.
        // Fields common to all commands.

        $data = [];

        $data['customerId'] = $this->getCustomerId();

        if ($this->getShopId()) {
            $data['shopId'] = $this->getShopId();
        }

        $data['toolkitPassword'] = $this->getToolkitPassword();
        $data['command'] = $this->command;
        $data['language'] = $this->getLanguage();

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

        $secret_position = ($this->getShopId() ? 3 : 2);

        $fingerprint_data = array_merge(
            array_slice($data, 0, $secret_position),
            ['secret' => $this->getSecret()],
            array_slice($data, $secret_position)
        );
        $data['requestFingerprint'] = $this->getRequestFingerprint($fingerprint_data);

        return $data;
    }

    /**
     * The response data will be an array here.
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
