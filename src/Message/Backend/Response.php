<?php

namespace Omnipay\Wirecard\Message\Backend;

/**
 * Backend command response.
 * In the event of an error, there may be one or more errors returned.
 * - Checkout Page will return the single final error.
 * - Checkout Seamless will return multiple errors, so the end user can be
 *   presented with multiple errors (against multiple inpout fields) to
 *   deal with.
 */

use Omnipay\Wirecard\Message\AbstractResponse;
use Omnipay\Wirecard\Message\HasDataTrait;

class Response extends AbstractResponse
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

    const PAYMENT_STATE_PAYMENT_APPROVED = 'payment_approved';
    const PAYMENT_STATE_PAYMENT_DEPOSITED = 'payment_deposited';
    const PAYMENT_STATE_PAYMENT_CLOSED = 'payment_closed';
    const PAYMENT_STATE_PAYMENT_APPROVALEXPIRED = 'payment_approvalexpired';

    const CREDIT_STATE_CREDIT_REFUNDED = 'credit_refunded';
    const CREDIT_STATE_CREDIT_CLOSED = 'credit_closed';

    protected $order_field_list = [
        'merchantNumber',
        'orderNumber',
        'paymentType',
        'amount',
        'brand',
        // Three digits OR three characters.
        'currency',
        'orderDescription',
        'acquirer',
        'contractNumber',
        'operationsAllowed',
        'orderReference',
        'customerStatement',
        'orderText',
        // Format "DD.MM.YYYY HH:MM:SS" e.g. 12 June: "12.06.2017 00:00:03"
        'timeCreated',
        'timeModified',
        'state',
        'sourceOrderNumber',
    ];

    protected $payment_field_list = [
        'merchantNumber',
        'paymentNumber',
        'orderNumber',
        'approvalCode',
        'batchNumber',
        'approveAmount',
        'depositAmount',
        'currency',
        'timeCreated',
        'timeModified',
        'state',
        'paymentType',
        'operationsAllowed',
        'gatewayReferenceNumber',
        'providerReferenceNumber',
        'avsResultCode',
        'avsResultMessage',
        'avsProviderResultCode',
        'avsProviderResultMessage',
        'idealConsumerName',
        'idealConsumerCity',
        'idealConsumerBIC',
        'idealConsumerIBAN',
        'idealConsumerAccountNumber',
        'paypalPayerID',
        'paypalBillingAgreementID',
        'paypalPayerEmail',
        'paypalPayerFirstName',
        'paypalPayerLastName',
        'paypalPayerAddressCountry',
        // ISO 3166-1 2-char
        'paypalPayerAddressCountryCode',
        'paypalPayerAddressCity',
        'paypalPayerAddressState',
        'paypalPayerAddressName',
        'paypalPayerAddressStreet1',
        'paypalPayerAddressStreet2',
        'paypalPayerZIP',
        'paypalPayerAddressStatus',
        'paypalPayerProtectionEligibility',
        'senderAccountOwner',
        'senderAccountNumber',
        'senderBankNumber',
        'senderBankName',
        'senderBIC',
        'senderIBAN',
        'senderCountry',
        'securityCriteria',
        'instrumentCountry',
    ];

    protected $credit_field_list = [
        'merchantNumber',
        'creditNumber',
        'orderNumber',
        'batchNumber',
        'amount',
        'currency',
        'timeCreated',
        'timeModified',
        'state',
        'operationsAllowed',
        'gatewayReferenceNumber',
        'providerReferenceNumber',
    ];

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
     * Command: deposit, refund, generateOrderNumber
     * The new or existing gateway transaction number.
     *
     * @return int Numeric with a variable length of up to 9 digits.
     */
    public function getTransactionReference()
    {
        // These are all equivalent. They are the number of the transaction
        // created on the gateway.
        return $this->getPaymentNumber()
            ?: $this->getCreditNumber()
            ?: $this->getOrderNumber();
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

    //
    // The following for fetchTransaction only.
    //

    /**
     * The count of orders.
     *
     * @return int The number of orders, zero if there are no orders.
     */
    public function getOrderCount()
    {
        return (int)$this->getDataValue('orders', 0);
    }

    /**
     * The array of order details, with nested payments and credits where available.
     * No attempt is made to parse this data into an object.
     * All elementd will be available in the array, regardless of whether the field is
     * present or not. The presence of a field will depend on payment type as well as
     * the state of the transaction.
     *
     * @return array The array of order details.
     */
    public function getOrders()
    {
        $orders = [];

        if ($orderCount = $this->getOrderCount()) {
            // The documentation lists field names using full stops as part separators.
            // The demo site uses underscores as part separators.
            // We will use them interchangeably, trying underscores first.

            for ($i = 1; $i <= $orderCount; $i++) {
                $order = [];

                foreach ($this->order_field_list as $part) {
                    // TODO: we then have potential "payments" and "credit" sub-lists.

                    $order[$part] = $this->getDataValue(
                        'order_' . $i . '_' . $part,
                        $this->getDataValue('order.' . $i . '.' . $part)
                    );
                }

                // Get the payment count for this order and collect any payments.
                // There will (for now) only be zero or one payment.

                $paymentCount = $this->getDataValue(
                    'order_' . $i . '_payments',
                    $this->getDataValue('order.' . $i . '.payments', 0)
                );

                $order['paymentCount'] = $paymentCount;
                $order['payments'] = [];
                $order['payments'][$i] = [];

                if ($paymentCount) {
                    for ($j = 1; $j <= $paymentCount; $j++) {
                        foreach ($this->payment_field_list as $payment_part) {
                            $order['payments'][$i][$payment_part] = $this->getDataValue(
                                'payment_' . $i . '_' . $j . '_' . $payment_part,
                                $this->getDataValue('payment.' . $i . '_' . $j . '.' . $payment_part)
                            );
                        }
                    }
                }

                // Get the credits count for this order and collect any credit details.
                // There may be zero or more credits.

                $creditCount = $this->getDataValue(
                    'order_' . $i . '_credits',
                    $this->getDataValue('order.' . $i . '.credits', 0)
                );

                $order['creditCount'] = $creditCount;
                $order['credits'] = [];
                $order['credits'][$i] = [];

                if ($creditCount) {
                    for ($j = 1; $j <= $creditCount; $j++) {
                        foreach ($this->credit_field_list as $credit_part) {
                            $order['credits'][$i][$credit_part] = $this->getDataValue(
                                'credit_' . $i . '_' . $j . '_' . $credit_part,
                                $this->getDataValue('credit.' . $i . '_' . $j . '.' . $credit_part)
                            );
                        }
                    }
                }

                $orders[] = $order;
            }
        }

        return $orders;
    }
}
