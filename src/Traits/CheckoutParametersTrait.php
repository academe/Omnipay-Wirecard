<?php

namespace Omnipay\Wirecard\Traits;

/**
 * Manage parameters shared betweem the gateway and the message levels.
 * These are parameters specific to Checkout Page and Checkoput Seamless.
 */

use Omnipay\Common\Exception\InvalidRequestException;

trait CheckoutParametersTrait
{
    /**
     * URL of your online shop where your information page regarding
     * de-activated JavaScript resides.
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
     * Window name of browser window where payment page is opened.
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
     * Check for duplicate requests done by your consumer. 
     * Treated as a boolean value.
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

    /**
     * Based on pre-selected payment method a sub-selection of financial
     * institutions regarding the pre-selected payment method.
     * enumerated value set.
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
     * URL to a CSS file on your server to perform customizations.
     */
    public function setCssUrl($value)
    {
        return $this->setParameter('cssUrl', $value);
    }

    public function getCssUrl()
    {
        return $this->getParameter('cssUrl');
    }

}
