<?php

namespace Omnipay\Wirecard\Message;

/**
 * Create a new authorisation from an existing authorised order.
 *
 * Some notes:
 * 1. Creating a new authorisation does not remove the old authorisation; that
 *    should be cancelled explictly otherwise they will mount up on the customers's
 *    account or card. Use voidAuthorise on the original authorisation to do this.
 * 2. A recuring authorisation can be taken up to 400 days after the initial auth.
 * 3. The intitial auth (or payment) must have a transactionIdentifier of INITIAL.
 * 4. This recur transactino can have a transactionIdentifier of INITIAL, SINGLE or
 *    RECUR.
 * 5. The authorisation created this way must be captured for payment to be made, or
 *    the BackendPageRecurPurchaseRequest can be used to automatically request a
 *    capture at day-end clearing.
 *
 * Although not affecting use, it should be noted that the signature must be created
 * by fields in a specific order. The front end and page transaction can use any order
 * so long as the field order is listed in the appropriate field.
 */

class BackendPageRecurPurchaseRequest extends AbstractBackendRequest
{
    protected $base_fingerprint_field_order = [
        'customerId',
        'shopId',
        'toolkitPassword',
        'secret',
        'command',
        'language',
        'orderNumber',
        'sourceOrderNumber',
        'autoDeposit',
        'orderDescription',
        'amount',
        'currency',
        'orderReference',
        'customerStatement',
        'mandateId',
        'mandateSignatureDate',
        'creditorId',
        'dueDate',
        'transactionIdentifier',
        'useIbanBic',
    ];

    protected $additional_fingerprint_field_order = [
        'consumerEmail',
        'consumerBirthDate',
        'consumerTaxIdentificationNumber',
        'consumerDriversLicenseNumber',
        'consumerDriversLicenseState',
        'consumerDriversLicenseCountry',
        'consumerBillingFirstName',
        'consumerBillingLastName',
        'consumerBillingAddress1',
        'consumerBillingAddress2',
        'consumerBillingCity',
        'consumerBillingState',
        'consumerBillingCountry',
        'consumerBillingZipCode',
        'consumerBillingPhone',
        'consumerBillingFax',
        'consumerShippingFirstName',
        'consumerShippingLastName',
        'consumerShippingAddress1',
        'consumerShippingAddress2',
        'consumerShippingCity',
        'consumerShippingState',
        'consumerShippingCountry',
        'consumerShippingZipCode',
        'consumerShippingPhone',
        'consumerShippingFax',
    ];

    /**
     * Automatically capture the authorised amount at the end of the day.
     * This makes the transaction a purchase rather than an authorisation.
     */
    protected $autoDeposit = 'yes';

    /**
     * The backend command to send.
     */
    protected $command = 'recurPayment';

    /**
     * Return fields specific to the command.
     */
    public function getCommandData()
    {
        $data = [];

        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();

        $data['orderDescription'] = $this->getDescription();

        $data['sourceOrderNumber'] = $this->getSourceOrderNumber();

        // Whether to auto-capture the authorised amount at end of the day.
        $data['autoDeposit'] = $this->autoDeposit;

        if ($this->getCustomerStatement()) {
            $data['customerStatement'] = $this->getCustomerStatement();
        }

        // Numeric, up to 9 digits.
        // Either leave it blank and a new one will be created, or reserve one using
        // createOrderNumber and use that.

        if ($this->getOrderNumber() || $this->getTransactionReference()) {
            $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();
        }

        // The orderReference is passed on to the financial institution for final
        // payment matching.

        if ($this->getOrderReference()) {
            $data['orderReference'] = $this->getOrderReference();
        }

        // Get details about the consumer, email, address etc.
        $data = array_merge($data, $this->getConsumerData());

        // Source orders based on SEPA Direct Debit
        //mandateId mandateSignatureDate transactionIdentifier
        if ($transactionIdentifier = $this->getTransactionIdentifier()) {
            $data['transactionIdentifier'] = $transactionIdentifier;
        }

        // Only for Wirecard Bank as acquirer
        // creditorId dueDate

        // Source orders based on PayPal
        // transactionIdentifier

        // SEPA Direct Debit for source orders based on SOFORT
        // useIbanBic transactionIdentifier

        // For Computop as acquirer
        // transactionIdentifier

        return $data;
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

        // Calculate and add the fingerprint, calculated using the specific
        // fields in the specific order.
        $field_order = array_merge($this->base_fingerprint_field_order, $this->additional_fingerprint_field_order);

        $fingerprint_fields = [];
        foreach($field_order as $field_name) {
            if (array_key_exists($field_name, $data)) {
                $fingerprint_fields[$field_name] = $data[$field_name];
            }
        }
        $data['requestFingerprint'] = $this->getRequestFingerprint($fingerprint_fields);

        // Remove the sectet now we have the fingerprint
        unset($data['secret']);

        return $data;
    }

    /**
     * The source order, orignally of type "INITIAL"
     */
    public function setSourceOrderNumber($value)
    {
        return $this->setParameter('sourceOrderNumber', $value);
    }

    public function getSourceOrderNumber()
    {
        return $this->getParameter('sourceOrderNumber');
    }

    /**
     * Values: AbstractCheckoutRequest::TRANSACTION_IDENTIFIER_SINGLE
     * or AbstractCheckoutRequest::TRANSACTION_IDENTIFIER_INITIAL
     */
    public function setTransactionIdentifier($value)
    {
        return $this->setParameter('transactionIdentifier', $value);
    }

    public function getTransactionIdentifier()
    {
        return $this->getParameter('transactionIdentifier');
    }
}
