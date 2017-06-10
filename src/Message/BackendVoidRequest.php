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

class BackendVoidRequest extends AbstractBackendRequest
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
        $data = $this->getBaseData();

        // Fields mandatory for the depositReversal (void) command.

        $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();
        $data['paymentNumber'] = $this->getPaymentNumber();

        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        // Remove the sectet now we have the fingerprint
        unset($data['secret']);

        return $data;
    }

    /**
     * Get the payment number.
     *
     * @return string
     */
    public function getPaymentNumber()
    {
        return $this->getParameter('paymentNumber');
    }

    /**
     * Sets payment number.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setPaymentNumber($value)
    {
        return $this->setParameter('paymentNumber', $value);
    }
}
