<?php

namespace Omnipay\Wirecard\Message;

/**
 * Complete a Wirecard Checkout Page purchase transaction on the
 * user returning to the merchant shop.
 * Experimentally, this one class covers both the request and the response,
 * since not further requests back to the gateway are needed.
 * The advantage of doing this is that all the results needed are in the
 * initial request object. A merchant site can still send() that message
 * and get the same message back.
 * It should be possible to extend this as the notification handler too. If
 * so, then the HasFingerprintTrait becomes redundant.
 */

use Omnipay\Common\Message\ResponseInterface as OmnipayResponseInterface;

class CheckoutPageCompletePurchase extends AbstractRequest implements OmnipayResponseInterface
{
    // The results will be signed, se we need to be able to
    // validate the fingerprint.
    use HasFingerprintTrait;

    // Helper functions for accessing the data values
    use HasDataTrait;

    /**
     * The transaction result will be sent through POST parameters.
     * As a consequence, the merchant site must be running SSL.
     */
    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    /**
     * Simple pass-through to the response object.
     */
    public function sendData($data)
    {
        return $this;
    }

    public function getPaymentState()
    {
        return $this->getDataValue('paymentState');
    }

    /**
     * We put the transaction ID into a custom field, which will be passed
     * through by the gateway to the notification data.
     */
    public function getTransactionId()
    {
        return $this->getDataValue(AbstractCheckoutPurchaseRequest::CUSTOM_FIELD_NAME_TRANSACTION_ID);
    }

    /**
     * There are three transaction reference values, and we use gatewayReferenceNumber
     * which comes from the gateway.
     * There is also the gatewayContractNumber from the gateway, and providerReferenceNumber
     * from the service provider.
     */
    public function getTransactionReference()
    {
        return $this->getDataValue('gatewayReferenceNumber');
    }

    // The following are for this class as a response.

    /**
     * This object is returned as a response to itself, so the request
     * that created it is itself.
     */
    public function getRequest()
    {
        return $this;
    }

    public function isRedirect()
    {
        return false;
    }

    /**
     * 
     */
    public function isSuccessful()
    {
        if (! $this->isValid()) {
            return false;
        }

        $paymentState = $this->getPaymentState();

        return (
            $paymentState === AbstractResponse::PAYMENT_STATE_SUCCESS
            || $paymentState === AbstractResponse::PAYMENT_STATE_PENDING
        );
    }

    /**
     * Is the transaction cancelled by the user?
     *
     * @inherit
     */
    public function isCancelled()
    {
        return ($this->getPaymentState() === AbstractResponse::PAYMENT_STATE_CANCEL);
    }

    public function isPending()
    {
        return ($this->getPaymentState() === AbstractResponse::PAYMENT_STATE_PENDING);
    }

    /**
     * There are no codes for Wirecard Checkout Page.
     */
    public function getCode()
    {
        return null;
    }

    /**
     * The system message.
     */
    public function getMessage()
    {
        if (! $this->isValid()) {
            return 'Validation of the fingerprint failed,';
        }

        if ($message = $this->getDataValue('message')) {
            return $message;
        }

        if ($this->isCancelled()) {
            return 'The transaction has been cancelled.';
        }

        // Successful *or* pending. Not sure if we need different messages for each.
        if ($this->isSuccessful()) {
            return 'The checkout process has been successful.';
        }
    }
}
