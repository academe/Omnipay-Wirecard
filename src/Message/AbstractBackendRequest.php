<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Abstract Backend Request.
 */

//use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

abstract class AbstractBackendRequest extends AbstractRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'placeholder';

    /**
     * Data common to all backend requests.
     * The order of all fields is critical when creating the singnatire.
     */
    public function getBaseData()
    {
        $data = [];

        $data['customerId'] = $this->getCustomerId();

        if ($this->getShopId()) {
            $data['shopId'] = $this->getShopId();
        }

        $data['toolkitPassword'] = $this->getToolkitPassword();

        // The secret will be removed after the fingerprint is calculated.
        $data['secret'] = $this->getSecret();

        $data['command'] = $this->command;
        $data['language'] = $this->getLanguage();

        return $data;
    }

    /**
     * Data is sent application/x-www-form-urlencoded
     */
    public function sendData($data)
    {
        return $this->createResponse($this->sendHttp($data));
    }

    /**
     * The response data will be an array here.
     */
    protected function createResponse($data)
    {
        return $this->response = new BackendResponse($this, $data);
    }
}
