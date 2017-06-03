<?php

namespace Omnipay\Wirecard\Message;

/**
 * Checkout SeemlessStorage Initialisarion Response.
 */

use Omnipay\Common\Message\RedirectResponseInterface;

class CheckoutSeamlessStorageInitResponse extends AbstractResponse implements RedirectResponseInterface
{
    // Helper functions for accessing the data values
    use HasDataTrait;

    // For each payment type, a list of the properties to be stored in the
    // data storage object.

    protected $dataStorageFields = [
        'CCARD' => [
            // Required:
            'pan',
            'expirationMonth',
            'expirationYear',
            // Optional:
            'cardholdername',
            'cardverifycode',
            'issueMonth',
            'issueYear',
            'issueNumber',
        ],
        'CCARD-MOTO' => [
            // Required:
            'pan',
            'expirationMonth',
            'expirationYear',
            // Optional:
            'cardholdername',
            'cardverifycode',
            'issueMonth',
            'issueYear',
            'issueNumber',
        ],
        'MAESTRO' => [
            // Required:
            'pan',
            'expirationMonth',
            'expirationYear',
            // Optional:
            'cardholdername',
            'cardverifycode',
            'issueMonth',
            'issueYear',
            'issueNumber',
        ],
        'SEPA-DD' => [
            // Required:
            'accountOwner',
            'bankAccountIban',
            'bankBic',
            // Optional:
            'bankName',
        ],
        'PBX' => [
            // Required:
            'payerPayboxNumber',
        ],
        'GIROPAY' => [
            // Required:
            'bankAccount',
            'bankNumber',
            // Optional:
            'accountOwner',
        ],
        'VOUCHER' => [
            // Required:
            'voucherId',
        ],
    ];

    // Anonymised versions of the storewd fields, fetching using getAnonymizedPaymentInformation()
    // on the response.

    protected $dataStorageFieldsAnonymous = [
        'CCARD' => [
            'anonymousPan',
            'maskedPan',
            'financialInstitution',
            'brand',
            'cardholdername',
            'expiry', // MM/YYYY
        ],
        'CCARD-MOTO' => [
            'anonymousPan',
            'maskedPan',
            'financialInstitution',
            'brand',
            'cardholdername',
            'expiry', // MM/YYYY
        ],
        'MAESTRO' => [
            'anonymousPan',
            'maskedPan',
            'financialInstitution',
            'brand',
            'cardholdername',
            'expiry', // MM/YYYY
        ],
        'SEPA-DD' => [
            'accountOwner',
            'bankAccountIban',
            'bankBic',
            //'bankName',
        ],
        'PBX' => [
            'payerPayboxNumber',
        ],
        'GIROPAY' => [
            'bankAccount',
            'bankNumber',
            'accountOwner',
        ],
        'VOUCHER' => [
            'voucherId',
        ],
    ];

    public function isSuccessful()
    {
        // If there is a storageId and we are not needing a redirect
        // then the request was successful.
        // Technically a redirect is a success, but the transaction is not yet complete.
        return (bool)$this->getStorageId() && ! $this->isRedirect();
    }

    /**
     *
     */
    public function getStorageId()
    {
        return $this->getDataValue('storageId');
    }

    /**
     *
     */
    public function getJavascriptUrl()
    {
        return $this->getDataValue('javascriptUrl');
    }

    /**
     * The count of error messages.
     *
     * @return int The number of errors, zero if there are no errors.
     */
    public function getErrorCount()
    {
        return (int)$this->getDataValue('errors');
    }

    /**
     * The array of error messages.
     *
     * @return array The array of error messages.
     */
    public function getErrors()
    {
        $errors = [];

        if ($error_count = $this->getErrorCount()) {
            // The documentation lists field names using full stops as part separators.
            // The demo site uses underscores as part separators.
            // We will use them interchangeably, trying underscores first.

            for ($i = 1; $i <= $error_count; $i++) {
                $error = [];

                foreach (['errorCode', 'message', 'consumerMessage', 'paySysMessage'] as $part) {
                    $error[$part] = $this->getDataValue(
                        'error_' . $i . '_' . $part,
                        $this->getDataValue('error.' . $i . '.' . $part)
                    );
                }
            }

            $errors[] = $error;
        }

        return $errors;
    }

    /**
     * The URL if a redirect is needed.
     */
    public function getRedirectUrl()
    {
        return $this->getDataValue('redirectUrl');
    }

    /**
     * 
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * 
     */
    public function getRedirectData()
    {
        return [];
    }

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return (bool)$this->getRedirectUrl();
    }
}
