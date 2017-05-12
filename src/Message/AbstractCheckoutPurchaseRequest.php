<?php

namespace Omnipay\Wirecard\Message;

/**
 * Purchase, shared methods for Checkout Page and Checkout Seamless.
 */

use Omnipay\Wirecard\AbstractShopGateway;
use Omnipay\Wirecard\Extend\ItemInterface;

abstract class AbstractCheckoutPurchaseRequest extends AbstractRequest
{
    const DUPLICATE_REQUEST_CHECK_YES = 'yes';
    const DUPLICATE_REQUEST_CHECK_NO = 'no';

    const TRANSACTION_IDENTIFIER_SINGLE = 'SINGLE';
    const TRANSACTION_IDENTIFIER_INITIAL = 'INITIAL';

    const AUTO_DEPOSIT_YES = 'yes';
    const AUTO_DEPOSIT_NO = 'no';

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
    public function setNoScriptInfoUrl($value)
    {
        return $this->setParameter('noScriptInfoUrl', $value);
    }

    public function getNoScriptInfoUrl()
    {
        return $this->getParameter('noScriptInfoUrl');
    }

    /**
     * 
     */
    public function setOrderNumber($value)
    {
        return $this->setParameter('orderNumber', $value);
    }

    public function getOrderNumber()
    {
        return $this->getParameter('orderNumber');
    }

    /**
     * 
     */
    public function setWindowName($value)
    {
        return $this->setParameter('windowName', $value);
    }

    public function getWindowName()
    {
        return $this->getParameter('windowName');
    }

    /**
     * A boolean value.
     */
    public function setDuplicateRequestCheck($value)
    {
        return $this->setParameter('duplicateRequestCheck', $value);
    }

    public function getDuplicateRequestCheck()
    {
        return $this->getParameter('duplicateRequestCheck');
    }

    /**
     *  TODO: validation (see constants)
     */
    public function setTransactionIdentifier($value)
    {
        return $this->setParameter('transactionIdentifier', $value);
    }

    public function getTransactionIdentifier()
    {
        return $this->getParameter('transactionIdentifier');
    }

    /**
     * 
     */
    public function setFinancialInstitution($value)
    {
        return $this->setParameter('financialInstitution', $value);
    }

    public function getFinancialInstitution()
    {
        return $this->getParameter('financialInstitution');
    }

    /**
     * 
     */
    public function setCssUrl($value)
    {
        return $this->setParameter('cssUrl', $value);
    }

    public function getCssUrl()
    {
        return $this->getParameter('cssUrl');
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
    public function setAutoDeposit($value)
    {
        return $this->setParameter('autoDeposit', $value);
    }

    public function getAutoDeposit()
    {
        return $this->getParameter('autoDeposit');
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
     * Construct the request data to send.
     *
     * @return array
     */
    public function getBaseData()
    {
        $data = array(
            'customerId' => $this->getCustomerId(),
            'language' => $this->getLanguage(),
            'shopId' => $this->getShopId(),
        );

        $data['paymentType'] = $this->getPaymentType() ?: static::PAYMENT_TYPE_SELECT;

        // Wirecard will accept either the three-letter currency code or the
        // numeric currency code. Omnipay rovides the letters version.
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();

        $data['orderDescription'] = $this->getDescription();
        $data['successUrl'] = $this->getReturnUrl();

        if ($this->getCancelUrl()) {
            $data['cancelUrl'] = $this->getCancelUrl();
        }

        /**
         * Optional gateway-specific URL.
         */
        if ($this->getFailureUrl()) {
            $data['failureUrl'] = $this->getFailureUrl();
        }

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

        if ($this->getTransactionId()) {
            $data['orderReference'] = $this->getTransactionId();
        }

        if ($this->getPendingUrl()) {
            $data['pendingUrl'] = $this->getPendingUrl();
        }

        /**
         * Optional parameters to tweak the remote UI.
         */

        if ($this->getNoScriptInfoUrl()) {
            $data['noScriptInfoUrl'] = $this->getNoScriptInfoUrl();
        }

        // CHECKME: numeric, up to 9 digits, but what exactly is it?
        if ($this->getOrderNumber()) {
            $data['orderNumber'] = $this->getOrderNumber();
        }

        if ($this->getWindowName()) {
            $data['windowName'] = $this->getWindowName();
        }

        // Boolean text 'yes' or 'no'
        if ($this->getDuplicateRequestCheck()) {
            $data['duplicateRequestCheck'] = (
                $this->getDuplicateRequestCheck()
                ? static::DUPLICATE_REQUEST_CHECK_YES
                : static::DUPLICATE_REQUEST_CHECK_NO
            );
        }

        // SINGLE or INITIA+L
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
            // The count of items in the basket.
            $data['basketItems'] = $items->count();

            $item_number = 0;
            foreach($items->getIterator() as $item) {
                $item_number++;

                $prefix = 'basketItem' . $item_number;

                $data[$prefix . 'Quantity'] = $item->getQuantity();
                $data[$prefix . 'Name'] = $item->getName();
                $data[$prefix . 'UnitGrossAmount'] = $item->getPrice();

                if ($item->getDescription()) {
                    $data[$prefix . 'Description'] = $item->getDescription();
                }

                if ($item instanceof ItemInterface) {
                    // THe extended item class supports additional fields.
                    $data[$prefix . 'UnitNetAmount'] = $item->getNetAmount() ?: $item->getPrice();
                    $data[$prefix . 'ArticleNumber'] = $item->getArticleNumber() ?: $item_number;
                    $data[$prefix . 'UnitTaxAmount'] = $item->getTaxAmount() ?: 0;
                    $data[$prefix . 'UnitTaxRate'] = $item->getTaxRate() ?: 0;

                    if ($item->getImageUrl()) {
                        $data[$prefix . 'ImageUrl'] = $item->getImageUrl();
                    }
                } else {
                    // These are defaulted for the standard Omnipay Item, as
                    // they are all required.
                    $data[$prefix . 'UnitNetAmount'] = $item->getPrice();
                    $data[$prefix . 'ArticleNumber'] = $item_number;
                    $data[$prefix . 'UnitTaxAmount'] = 0;
                    $data[$prefix . 'UnitTaxRate'] = 0;
                }
            }
        }

        // Feature specific parameters.

        if ($this->getAutoDeposit()) {
            $data['autoDeposit'] = (
                $this->getAutoDeposit()
                ? static::AUTO_DEPOSIT_YES
                : static::AUTO_DEPOSIT_NO
            );
        }

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

        return $data;
    }

    public function sendData($data)
    {
        return $this->createResponse($data);
    }
}
