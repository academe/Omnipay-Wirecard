<?php

namespace Omnipay\Wirecard\Message;

/**
 * Backend command response.
 * In the event of an error, there may be one or more errors returned.
 * - Checkout Page will return the single final error.
 * - Checkout Seamless will return multiple errors, so the end user can be
 *   presented with multiple errors (against multiple inpout fields) to
 *   deal with.
 */

class BackendResponse extends AbstractResponse
{
    // Helper functions for accessing the data values
    use HasDataTrait;

    /**
     * Status codes:
     *  0 Operation successfully done.
     *  1 Error during request parameter verification.
     *  2 Execution of operation denied.
     *  3 Error within external financial institution.
     *  4 Internal error. 
     */

    const STATUS_SUCCESS        = 0;
    const STATUS_INVALID        = 1;
    const STATUS_DENIED         = 2;
    const STATUS_ERROR_EXTERNAL = 3;
    const STATUS_ERROR_INTERNAL = 4;

    /**
     * The raw status code.
     */
    public function getStatus()
    {
        return $this->getDataValue('status');
    }

    // Error details common to all commands wrt Checkout Page.

    /**
     * The raw numeric error code.
     */
    public function getErrorCode()
    {
        return $this->getDataValue('errorCode');
    }

    /**
     * The raw (error) message from the gateway.
     */
    public function getMessage()
    {
        return $this->getDataValue('message');
    }

    /**
     * The raw (error) message from the reote merchant or system.
     */
    public function getPaySysMessage()
    {
        return $this->getDataValue('paySysMessage');
    }

    // TODO: an array of error details may be returned for Checkout Seamless

    /**
     * Command: deposit
     * A new payment number is returned if a new payment object has been
     * created due to a split capture.
     *
     * @return int Numeric with a variable length of up to 9 digits.
     */
    public function getPaymentNumber()
    {
        return $this->getDataValue('paymentNumber');
    }

    /**
     * Command: refund
     * Number of the credit note.
     *
     * @return int Numeric with a variable length of up to 9 digits.
     */
    public function getCreditNumber()
    {
        return $this->getDataValue('creditNumber');
    }

    /**
     * Command: deposit, refund
     * The new or existing gateway transaction number.
     *
     * @return int Numeric with a variable length of up to 9 digits.
     */
    public function getTransactionReference()
    {
        return $this->getPaymentNumber ?: $this->getCreditNumber();
    }

    /**
     * The simple success/fail interpretation for Omnipay.
     *
     * @return bopol True if the command was accepted and processed successfuly.
     */
    public function isSuccessful()
    {
        return ($this->getStatus() == static::STATUS_SUCCESS);
    }

    /**
     * Command: createOrderNumber
     * The orderNumber reserved for a new transaction.
     *
     * @return int Numeric with a variable length of up to 9 digits.
     */
    public function getOrderNumber()
    {
        return $this->getDataValue('orderNumber');
    }
}
