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

class BackendPageVoidRequest extends AbstractBackendRequest
{
    /**
     * The backend command to send.
     * CHECKME: Not sure what relationship this has with approveReversal. It seems that
     * depositReversal takes a captured transactgion back to the approval stage (but
     * only before the end of the day), but does not void the transaction completely.
     * So maybe a full voids needs to do both depositReversal then a approveReversal?
     */
    protected $command = 'depositReversal';

    /**
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        $data = [];

        $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();
        $data['paymentNumber'] = $this->getPaymentNumber();

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
