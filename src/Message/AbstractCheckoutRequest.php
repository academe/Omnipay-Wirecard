<?php

namespace Omnipay\Wirecard\Message;

/**
 * Purchase, shared methods for Checkout Page and Checkout Seamless.
 */

use Omnipay\Wirecard\Traits\CheckoutParametersTrait;
use Omnipay\Wirecard\AbstractShopGateway;

abstract class AbstractCheckoutRequest extends AbstractRequest
{
    // Custom parameters implemented for the Checkout APIs.
    use CheckoutParametersTrait;

    const DUPLICATE_REQUEST_CHECK_YES = 'yes';
    const DUPLICATE_REQUEST_CHECK_NO = 'no';

    /**
     * SINGLE - a single, one-off transaction.
     * INITIAL - the first of a series of recurring transactions.
     */
    const TRANSACTION_IDENTIFIER_SINGLE = 'SINGLE';
    const TRANSACTION_IDENTIFIER_INITIAL = 'INITIAL';

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
     * The orderReference is sent right through to the financial
     * institution.
     * It is not necessarily the same as the transactionId, which
     * should only go as far as the gateway.
     */
    public function setOrderReference($value)
    {
        return $this->setParameter('orderReference', $value);
    }

    public function getOrderReference()
    {
        return $this->getParameter('orderReference');
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
    public function setConsumerTaxIdentificationNumber($value)
    {
        return $this->setParameter('consumerTaxIdentificationNumber', $value);
    }

    public function getConsumerTaxIdentificationNumber()
    {
        return $this->getParameter('consumerTaxIdentificationNumber');
    }

    /**
     * 
     */
    public function setConsumerDriversLicenseNumber($value)
    {
        return $this->setParameter('consumerDriversLicenseNumber', $value);
    }

    public function getConsumerDriversLicenseNumber()
    {
        return $this->getParameter('consumerDriversLicenseNumber');
    }

    /**
     * 
     */
    public function setConsumerDriversLicenseState($value)
    {
        return $this->setParameter('consumerDriversLicenseState', $value);
    }

    public function getConsumerDriversLicenseState()
    {
        return $this->getParameter('consumerDriversLicenseState');
    }

    /**
     * 
     */
    public function setConsumerDriversLicenseCountry($value)
    {
        return $this->setParameter('consumerDriversLicenseCountry', $value);
    }

    public function getConsumerDriversLicenseCountry()
    {
        return $this->getParameter('consumerDriversLicenseCountry');
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

        // Numeric, up to 9 digits, but what exactly is it?
        // Must be unique and each value can only be used once, so be careful about
        // tieing it back to merchant site order numbers that mat require more than
        // one payment or payment *attempts*.

        if ($this->getOrderNumber()) {
            $data['orderNumber'] = $this->getOrderNumber();
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

        // static::TRANSACTION_IDENTIFIER_SINGLE or static::TRANSACTION_IDENTIFIER_INITIA+L
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

        /**
         * Consumer details.
         * Mainly optional, but may be required for some payment types.
         */

        if ($this->getConsumerTaxIdentificationNumber()) {
            $data['consumerTaxIdentificationNumber'] = $this->getConsumerTaxIdentificationNumber();
        }

        if ($this->getConsumerDriversLicenseNumber()) {
            $data['consumerDriversLicenseNumber'] = $this->getConsumerDriversLicenseNumber();
        }

        // Alphabetic with a fixed length of 2 for US and CA, otherwise up to 40.
        if ($this->getConsumerDriversLicenseState()) {
            $data['consumerDriversLicenseState'] = $this->getConsumerDriversLicenseState();
        }

        // ISO 2-letter
        if ($this->getConsumerDriversLicenseCountry()) {
            $data['consumerDriversLicenseCountry'] = $this->getConsumerDriversLicenseCountry();
        }

        if ($card = $this->getCard()) {
            if ($card->getEmail()) {
                $data['consumerEmail'] = $card->getEmail();
            }

            // Foxed format YYYY-MM-DD
            if ($card->getBirthday()) {
                $data['consumerBirthDate'] = $card->getgetBirthday('Y-m-d');
            }

            // Billing details.

            if ($card->getBillingFirstName()) {
                $data['consumerBillingFirstname'] = $card->getBillingFirstName();
            }

            if ($card->getBillingLastName()) {
                $data['consumerBillingLastname'] = $card->getBillingLastName();
            }

            if ($card->getBillingAddress1()) {
                $data['consumerBillingAddress1'] = $card->getBillingAddress1();
            }

            if ($card->getBillingAddress2()) {
                $data['consumerBillingAddress2'] = $card->getBillingAddress2();
            }

            if ($card->getBillingCity()) {
                $data['consumerBillingCity'] = $card->getBillingCity();
            }

            // Fixed length 2-letter string. Possibly US-only?
            if ($card->getBillingState()) {
                $data['consumerBillingState'] = $card->getBillingState();
            }

            // Fixed length 2-letter string. Possibly US-only?
            if ($card->getBillingCountry()) {
                $data['consumerBillingCountry'] = $card->getBillingCountry();
            }

            // Fixed length 2-letter string. Possibly US-only?
            if ($card->getBillingPostcode()) {
                $data['consumerBillingZipCode'] = $card->getBillingPostcode();
            }

            if ($card->getBillingPhone()) {
                $data['consumerBillingPhone'] = $card->getBillingPhone();
            }

            if ($card->getBillingFax()) {
                $data['consumerBillingFax'] = $card->getBillingFax();
            }

            // Shipping details.

            if ($card->getShippingFirstName()) {
                $data['consumerShippingFirstname'] = $card->getShippingFirstName();
            }

            if ($card->getShippingLastName()) {
                $data['consumerShippingLastname'] = $card->getShippingLastName();
            }

            if ($card->getShippingAddress1()) {
                $data['consumerShippingAddress1'] = $card->getShippingAddress1();
            }

            if ($card->getShippingAddress2()) {
                $data['consumerShippingAddress2'] = $card->getShippingAddress2();
            }

            if ($card->getShippingCity()) {
                $data['consumerShippingCity'] = $card->getShippingCity();
            }

            // Fixed length 2-letter string. Possibly US-only?
            if ($card->getShippingState()) {
                $data['consumerShippingState'] = $card->getShippingState();
            }

            // Fixed length 2-letter string. Possibly US-only?
            if ($card->getShippingCountry()) {
                $data['consumerShippingCountry'] = $card->getShippingCountry();
            }

            // Fixed length 2-letter string. Possibly US-only?
            if ($card->getShippingPostcode()) {
                $data['consumerShippingZipCode'] = $card->getShippingPostcode();
            }

            if ($card->getShippingPhone()) {
                $data['consumerShippingPhone'] = $card->getShippingPhone();
            }

            if ($card->getShippingFax()) {
                $data['consumerShippingFax'] = $card->getShippingFax();
            }
        }

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
