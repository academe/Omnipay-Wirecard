<?php

namespace Omnipay\Wirecard\Message\Checkout;

/**
 * Purchase, shared methods for Checkout Page and Checkout Seamless.
 */

use Omnipay\Wirecard\Traits\CheckoutParametersTrait;
use Omnipay\Wirecard\AbstractShopGateway;
use Omnipay\Wirecard\Message\AbstractRequest as MessageAbstractRequest;

abstract class AbstractRequest extends MessageAbstractRequest
{
    // Custom parameters implemented for the Checkout APIs.
    use CheckoutParametersTrait;

    const DUPLICATE_REQUEST_CHECK_YES = 'yes';
    const DUPLICATE_REQUEST_CHECK_NO = 'no';

    /**
     * SINGLE - a single, one-off transaction.
     * INITIAL - the first of a series of recurring transactions.
     *
     * Move to trait CheckoutParametersTrait?
     */
    const TRANSACTION_IDENTIFIER_SINGLE = 'SINGLE';
    const TRANSACTION_IDENTIFIER_INITIAL = 'INITIAL';
    //const TRANSACTION_IDENTIFIER_RECUR = 'RECUR';

    const AUTO_DEPOSIT_YES = 'yes';
    const AUTO_DEPOSIT_NO = 'no';

    // The name of the custom field the transacton ID will go into.
    const CUSTOM_FIELD_NAME_TRANSACTION_ID = 'omnipay_transactionId';

    /**
     * The Payment Type will default to SELECT if not set.
     * Permitted values are static::PAYMENT_TYPE_*
     */
    public function setPaymentType($value)
    {
        return $this->setParameter('paymentType', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }

    /**
     * This is the Omnipay way of setting the payment type.
     */
    public function setPaymentMethod($value)
    {
        return $this->setPaymentType($value);
    }

    public function getPaymentMethod()
    {
        return $this->getPaymentType();
    }

    /**
     * 
     */
    public function setConfirmMail($value)
    {
        return $this->setParameter('confirmMail', $value);
    }

    public function getConfirmMail()
    {
        return $this->getParameter('confirmMail');
    }

    /**
     * 
     */
    public function setRiskSuppress($value)
    {
        return $this->setParameter('riskSuppress', $value);
    }

    public function getRiskSuppress()
    {
        return $this->getParameter('riskSuppress');
    }

    /**
     * 
     */
    public function setRiskConfigAlias($value)
    {
        return $this->setParameter('riskConfigAlias', $value);
    }

    public function getRiskConfigAlias()
    {
        return $this->getParameter('riskConfigAlias');
    }

    /**
     * Get the ISO 639-1 (two-letter) language code.
     * This may need to be extracted from a longer supplied language code.
     */
    protected function getLanguageCode()
    {
        $code = $this->getLanguage();

        // If the language contains more than two characters, then *assume* it
        // is a longer ISO code, e.g. "en-GB" or "de-DE" (RFC 5646).
        // We could add further validation at this stage, but we'll leave that
        // for a future Omnipay if we can get a language object in to stadardise
        // the language handling.

        if (is_string($code)) {
            if (strlen($code) > 2) {
                $code = substr($code, 0, 2);
            }

            $code = strtolower($code);
        }

        return $code;
    }

    /**
     * Construct the request data to send.
     *
     * @return array
     */
    public function getBaseData()
    {
        $data = array(
            'customerId' => $this->getCustomerId(),
            'language' => $this->getLanguageCode(),
            'shopId' => $this->getShopId(),
        );

        $data['paymentType'] = $this->getPaymentType() ?: static::PAYMENT_TYPE_SELECT;

        // Wirecard will accept either the three-letter currency code or the
        // numeric currency code. Omnipay rovides the letters version.
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();

        $data['orderDescription'] = $this->getDescription();

        // All the different return state URLs are mandatory, so default
        // any that have not beedn provided.

        $data['successUrl'] = $this->getReturnUrl();
        $data['cancelUrl'] = $this->getCancelUrl() ?: $this->getReturnUrl();
        $data['failureUrl'] = $this->getFailureUrl() ?: $this->getReturnUrl();
        $data['pendingUrl'] = $this->getPendingUrl() ?: $this->getReturnUrl();

        /**
         * Optional gateway-specific URL.
         */

        if ($this->getServiceUrl()) {
            $data['serviceUrl'] = $this->getServiceUrl();
        }

        /**
         * Optional but highly recommended.
         */

        if ($this->getNotifyUrl()) {
            $data['confirmUrl'] = $this->getNotifyUrl();
        }

        if ($this->getCustomerStatement()) {
            $data['customerStatement'] = $this->getCustomerStatement();
        }

        // The orderReference is passed on to the financial institution for final
        // payment matching.

        if ($this->getOrderReference()) {
            $data['orderReference'] = $this->getOrderReference();
        }

        /**
         * Optional parameters to tweak the remote UI.
         */

        if ($this->getNoScriptInfoUrl()) {
            $data['noScriptInfoUrl'] = $this->getNoScriptInfoUrl();
        }

        // Numeric, up to 9 digits.
        // Either leave it blank and a new one will be created, or reserve one using
        // createOrderNumber and use that.

        if ($this->getOrderNumber() || $this->getTransactionReference()) {
            $data['orderNumber'] = $this->getOrderNumber() ?: $this->getTransactionReference();
        }

        if ($this->getWindowName()) {
            $data['windowName'] = $this->getWindowName();
        }

        // Boolean delivered as 'yes' or 'no'
        if ($this->getDuplicateRequestCheck()) {
            $data['duplicateRequestCheck'] = (
                $this->getDuplicateRequestCheck()
                ? static::DUPLICATE_REQUEST_CHECK_YES
                : static::DUPLICATE_REQUEST_CHECK_NO
            );
        }

        // static::TRANSACTION_IDENTIFIER_SINGLE or static::TRANSACTION_IDENTIFIER_INITIAL
        if ($this->getTransactionIdentifier()) {
            $data['transactionIdentifier'] = $this->getTransactionIdentifier();
        }

        // Enumeration TODO find the list
        // Based on pre-selected payment method a sub-selection of financial
        // institutions regarding the pre-selected payment method.
        if ($this->getFinancialInstitution()) {
            $data['financialInstitution'] = $this->getFinancialInstitution();
        }

        if ($this->getCssUrl()) {
            $data['cssUrl'] = $this->getCssUrl();
        }

        // Get details about the consumer, email, address etc.
        $data = array_merge($data, $this->getConsumerData());

        // Shopping basket items (with an extended basket for additional fields).

        if ($items = $this->getItems()) {
            $data = array_merge($data, $this->itemsAsArray($items));
        }

        // Feature specific parameters.

        if ($this->getConfirmMail()) {
            $data['confirmMail'] = $this->getConfirmMail();
        }

        // Fraud protection suite.

        if ($this->getRiskSuppress()) {
            $data['riskSuppress'] = $this->getRiskSuppress();
        }

        if ($this->getRiskConfigAlias()) {
            $data['riskConfigAlias'] = $this->getRiskConfigAlias();
        }

        // TODO: Custom fields (probably a collection of names and values).
        // It looks like custom fields are the only reliable way to tie back
        // the back-channel notifications to the transaction in storage.
        // We may need to create a predefined custom field for the
        // transactionId, so the notification handler knows where to find it.

        // Put the transaction ID into a custom field.
        if ($this->getTransactionId()) {
            $data[static::CUSTOM_FIELD_NAME_TRANSACTION_ID] = $this->getTransactionId();
        }

        return $data;
    }
}
