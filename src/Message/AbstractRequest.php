<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Abstract Request.
 */

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

abstract class AbstractRequest extends OmnipayAbstractRequest
{
    // Access to the constants as lists.
    use HasConstantListsTrait;

    /**
     * Supported payment types.
     */

    // The consumer may select one of the activated payment methods directly in Wirecard Checkout Page.
    // SELECT is only available for Wirecard Checkout Page.
    const PAYMENT_TYPE_SELECT = 'SELECT';
    // Bancontact/Mister Cash
    const PAYMENT_TYPE_BANCONTACT_MISTERCASH = 'BANCONTACT_MISTERCASH';
    // Credit Card, Maestro SecureCode
    const PAYMENT_TYPE_CCARD = 'CCARD';
    // Credit Card - Mail Order and Telephone Order
    const PAYMENT_TYPE_CCARD_MOTO = 'CCARD-MOTO';
    // eKonto
    const PAYMENT_TYPE_EKONTO = 'EKONTO';
    // ePay.bg
    const PAYMENT_TYPE_EPAY_BG = 'EPAY_BG';
    // eps-Überweisung
    const PAYMENT_TYPE_EPS = 'EPS';
    // giropay
    const PAYMENT_TYPE_GIROPAY = 'GIROPAY';
    // iDEAL
    const PAYMENT_TYPE_IDL = 'IDL';
    // Installment: payolution or Installment: RatePAY
    const PAYMENT_TYPE_INSTALLMENT = 'INSTALLMENT';
    // Invoice: payolution, Invoice: RatePAY or Invoice by Wirecard
    const PAYMENT_TYPE_INVOICE = 'INVOICE';
    // Maestro SecureCode
    const PAYMENT_TYPE_MAESTRO = 'MAESTRO';
    // Masterpass
    const PAYMENT_TYPE_MASTERPASS = 'MASTERPASS';
    // moneta.ru
    const PAYMENT_TYPE_MONETA = 'MONETA';
    // Przelewy24
    const PAYMENT_TYPE_PRZELEWY24 = 'PRZELEWY24';
    // PayPal
    const PAYMENT_TYPE_PAYPAL = 'PAYPAL';
    // paybox
    const PAYMENT_TYPE_PBX = 'PBX';
    // POLi
    const PAYMENT_TYPE_POLI = 'POLI';
    // paysafecard
    const PAYMENT_TYPE_PSC = 'PSC';
    // @Quick
    const PAYMENT_TYPE_QUICK = 'QUICK';
    // SEPA Direct Debit
    const PAYMENT_TYPE_SEPA_DD = 'SEPA-DD';
    // Skrill Digital Wallet
    const PAYMENT_TYPE_SKRILLWALLET = 'SKRILLWALLET';
    // SOFORT
    const PAYMENT_TYPE_SOFORTUEBERWEISUNG = 'SOFORTUEBERWEISUNG';
    // TatraPay
    const PAYMENT_TYPE_TATRAPAY = 'TATRAPAY';
    // Trustly
    const PAYMENT_TYPE_TRUSTLY = 'TRUSTLY';
    // TrustPay
    const PAYMENT_TYPE_TRUSTPAY = 'TRUSTPAY';
    // My Voucher
    const PAYMENT_TYPE_VOUCHER = 'VOUCHER';

    /**
     * The Customer ID is the merchant account ID string.
     */
    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    /**
     * The Shop ID.
     */
    public function setShopId($value)
    {
        return $this->setParameter('shopId', $value);
    }

    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    /**
     * The Language ID is ISO 2-char code.
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Text displayed on bank statement issued to your consumer by
     * the financial service provider.
     */
    public function setCustomerStatement($value)
    {
        return $this->setParameter('customerStatement', $value);
    }

    public function getCustomerStatement()
    {
        return $this->getParameter('customerStatement');
    }

    /**
     * Get the request failure URL.
     *
     * @return string
     */
    public function getFailureUrl()
    {
        return $this->getParameter('failureUrl');
    }

    /**
     * Sets the request failure URL.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setFailureUrl($value)
    {
        return $this->setParameter('failureUrl', $value);
    }

    /**
     * Get the request service URL.
     *
     * @return string
     */
    public function getServiceUrl()
    {
        return $this->getParameter('serviceUrl');
    }

    /**
     * Sets the request service URL.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setServiceUrl($value)
    {
        return $this->setParameter('serviceUrl', $value);
    }

    /**
     * Get the pending URL.
     *
     * @return string
     */
    public function getPendingUrl()
    {
        return $this->getParameter('pendingUrl');
    }

    /**
     * Sets the pending URL.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setPendingUrl($value)
    {
        return $this->setParameter('pendingUrl', $value);
    }

    /**
     * Return teh full list of supported payment types.
     *
     * @return array API values keyed by the constant name.
     */
    public function getPaymentTypes()
    {
        return $this->constantList('PAYMENT_TYPE');
    }
}
