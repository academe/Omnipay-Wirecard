<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Abstract Request.
 */

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Wirecard\Extend\ItemInterface;
use Omnipay\Common\ItemBag;

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
     * Leave empty if there is only one shop set up.
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
     * The Toolkit Password.
     * Used only for backend functions.
     */
    public function setToolkitPassword($value)
    {
        return $this->setParameter('toolkitPassword', $value);
    }

    public function getToolkitPassword()
    {
        return $this->getParameter('toolkitPassword');
    }

    /**
     * The Language ID is lower case ISO 2-char code.
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
     * Return the fingerprint order string, based on the field
     * names (the keys) of the data to send.
     *
     * @param array $data The key/value data to send
     * @return string Comma-separated list of field names
     */
    public function getRequestFingerprintOrder($data)
    {
        $order = implode(',', array_keys($data));

        // Two additional fields will be included in the hash.
        $order .= ',requestFingerprintOrder';

        // If the secret is not already in the data (temporarily) then
        // add it to the end for the fingterprint hash.
        if (! array_key_exists('secret', $data)) {
            $order .= ',secret';
        }

        return $order;
    }

    /**
     * Calculates the fintgerprint hash of the data to send.
     * It is assumed that the requestFingerprintOrder field has already
     * been added to this data.
     *
     * @param array $data The key/value data to send
     * @return string Fingerprint hash
     */
    function getRequestFingerprint($data)
    {
        $secret = $this->getSecret();

        $fields = implode('', array_values($data));

        // Add the secret to the string to hash, if it is not already in the
        // data.
        // It will never be sent with the data, but may be inserted just for the
        // signature creation.

        if (! array_key_exists('secret', $data)) {
            $fields .= $secret;
        }

        return hash_hmac('sha512', $fields, $secret);
    }

    /**
     * The secret for hashing.
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * CHECKME: is this supplied by the merchant site, or generated by the 
     * gateway? Or maybe optionally either?
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
     * Convert a collection of items to an array, as required to send to the gateway.
     */
    public function itemsAsArray(ItemBag $items)
    {
        $data = [];

        // The count of items in the basket.
        //$data['basketItems'] = $items->count();

        $item_number = 0;
        foreach($items->getIterator() as $item) {
            // Each item is sequentially numbered with a 1-based index.
            $item_number++;

            $prefix = 'basketItem' . $item_number;

            $data[$prefix . 'Quantity'] = $item->getQuantity();
            $data[$prefix . 'Name'] = $item->getName();
            $data[$prefix . 'UnitGrossAmount'] = $item->getPrice();

            // The description is optional.
            if ($item->getDescription()) {
                $data[$prefix . 'Description'] = $item->getDescription();
            }

            if ($item instanceof ItemInterface) {
                // The extended item class supports additional fields.
                $data[$prefix . 'UnitNetAmount'] = $item->getNetAmount() ?: $item->getPrice();
                $data[$prefix . 'ArticleNumber'] = $item->getArticleNumber() ?: $item_number;
                $data[$prefix . 'UnitTaxAmount'] = $item->getTaxAmount() ?: 0;
                $data[$prefix . 'UnitTaxRate'] = $item->getTaxRate() ?: 0;

                // The image URL is is optional.
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

        // All keys are in alphanumeric order, except for the basket count,
        // which is tagged onto the start.
        // The backend functions require this strict order.

        ksort($data);
        $data = array_merge(['basketItems' => $items->count()], $data);

        return $data;
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
     * Return the full list of supported payment types.
     * Not all these may be actuvated for the account.
     *
     * @return array API values keyed by the constant name.
     */
    public function getPaymentTypes()
    {
        return $this->constantList('PAYMENT_TYPE');
    }

    public function getPaymentMethods()
    {
        return $this->getPaymentTypes();
    }
}
