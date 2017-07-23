<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Seamless Recur Payment Request.
 * Cancel an authorised payment completely.
 */

class BackendSeamlessRecurPurchaseRequest extends BackendPageRecurPurchaseRequest
{
    protected $base_fingerprint_field_order = [
        'customerId',
        'shopId',
        'password',
        'secret',
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

    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/recurPayment';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
