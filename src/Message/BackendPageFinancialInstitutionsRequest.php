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
     * Collect the data together to send to the Gateway.
     */
    public function getData()
    {
        $data = $this->getBaseData();

        $data['paymentType'] = $this->getPaymentType();

        // TODO: optional parameters bankCountry and transactionType

        $data['requestFingerprint'] = $this->getRequestFingerprint($data);

        // Remove the secret now we have the fingerprint
        unset($data['secret']);

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
