<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Void Request.
 */

class BackendPageFinancialInstitutionsRequest extends AbstractBackendRequest
{
    /**
     * The backend command to send.
     */
    protected $command = 'getFinancialInstitutions';

    /**
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        $data = [];

        $data['paymentType'] = $this->getPaymentType();

        // TODO: optional parameters bankCountry and transactionType

        return $data;
    }

    /**
     * Get the payment type.
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }

    /**
     * Sets payment type.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setPaymentType($value)
    {
        return $this->setParameter('paymentType', $value);
    }
}
