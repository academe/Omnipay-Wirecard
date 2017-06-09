<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Void Request.
 *
 * A transaction can be voided before it is transferredby the financial
 * merchant, usually at midnight the day the transaction was captured,
 * or anytime before it is captured.
 * If the payment is just authorised, then it can be voided givem just
 * the orderNumber. This is the "approveReversal" command.
 * Once captured (and before tranferred by the financial institution)
 * it can be voided using the "depositReversal" command. This command
 * needs both the orderNumber and the paymentNumber.
 * Note this also means that a transaction with multiple payment parts
 * can have just some parts voided.
 * After that, to undo the transaction a refund must be issued.
 */

//use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

class BackendVoidRequest extends AbstractRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'depositReversal';

    protected $endpoint = 'https://checkout.wirecard.com/page/toolkit.php';

    /**
     * Collect the data together to send to the Gateway.
     */
    public function getData()
    {
        $data = [];

        // TODO: Need orderNumber and paymentNumber.
        // TODO: fingerprint is needed too.

        $data['customerId'] = $this->getCustomerId();

        if ($this->getShopId()) {
            $data['shopId'] = $this->getShopId();
        }

        $data['toolkitPassword'] = $this->getToolkitPassword();
        $data['command'] = $this->command;
        $data['language'] = $this->getLanguage();

        // Fields mandatory for the deposit command.

        $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();

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
