<?php

namespace Omnipay\Wirecard\Message\Backend;

/**
 * Wirecard Abstract Backend Request.
 */

use Omnipay\Wirecard\Message\AbstractRequest as CommonAbstractRequest;
//use Omnipay\Wirecard\Message\BackendResponse;

abstract class AbstractRequest extends CommonAbstractRequest
{
    /**
     * The backend (Page) command to send.
     */
    protected $command = '';

    /**
     * The default endpoint for all Checkout Page commands.
     */
    protected $endpoint = 'https://checkout.wirecard.com/page/toolkit.php';

    /**
     * Data common to all backend requests.
     * The order of all fields is critical when creating the singnatire.
     * CHECKME: the order of these required base fields seems to vary between the
     * Checkout Page and Checkout Seamless APIs. Checkout Seamless does not seem to
     * have a "command" parameter for deposit and refund at least, and uses "password"
     * instead of toolkit password. The command instead is encoded in the URL.
     * e.g. deposit Page starts: customerId, shopId, toolkitPassword, secret, command, language
     * and deposit Seamless starts: customerId, shopId, password, secret, language
     */
    public function getBaseData()
    {
        $data = [];

        $data['customerId'] = $this->getCustomerId();

        if ($this->getShopId()) {
            $data['shopId'] = $this->getShopId();
        }

        // Seamless uses a different password field name.
        if ($this->command) {
            $data['toolkitPassword'] = $this->getToolkitPassword();
        } else {
            $data['password'] = $this->getPassword() ?: $this->getToolkitPassword();
        }

        // The secret will be removed after the fingerprint is calculated.
        $data['secret'] = $this->getSecret();

        // Seamless does not have a command.
        if ($this->command) {
            $data['command'] = $this->command;
        }

        $data['language'] = $this->getLanguage();

        return $data;
    }

    /**
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        return [];
    }

    /**
     * Collect the data together to send to the Gateway.
     */
    public function getData()
    {
        // Start with the common base data.
        $data = $this->getBaseData();

        // Add in command-specific data.
        $data = array_merge($data, $this->getCommandData());

        // Calculate the fingerprint with the secret in-situ and put it on the end.
        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        // Remove the sectet now we have the fingerprint
        unset($data['secret']);

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
        return $this->response = new Response($this, $data);
    }
}
