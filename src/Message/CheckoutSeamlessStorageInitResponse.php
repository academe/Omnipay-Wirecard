<?php

namespace Omnipay\Wirecard\Message;

/**
 * Checkout SeemlessStorage Initialisarion Response.
 */

use Omnipay\Common\Message\RedirectResponseInterface;

class CheckoutSeamlessStorageInitResponse extends AbstractResponse
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

    /**
     * The function name to store the credentials on the remote data storage.
     */
    protected $dataStorageStoreFunctionName = [
        'CCARD' => 'storeCreditCardInformation',
        'CCARD-MOTO' => 'storeCreditCardMotoInformation',
        'MAESTRO' => 'storeMaestroInformation',
        'SEPA-DD' => 'storeSepaDdInformation',
        'PBX' => 'storePayboxInformation',
        'GIROPAY' => 'storeGiropayInformation',
        'VOUCHER' => 'storeVoucherInformation',
    ];

    public function getStorageFields()
    {
        if (isset($this->dataStorageFields[$this->getPaymentMethod()])) {
            return $this->dataStorageFields[$this->getPaymentMethod()];
        }

        return [];
    }

    public function getStorageFieldsAnonymous()
    {
        if (isset($this->dataStorageFieldsAnonymous[$this->getPaymentMethod()])) {
            return $this->dataStorageFieldsAnonymous[$this->getPaymentMethod()];
        }

        return [];
    }

    public function getDataStorageStoreFunctionName()
    {
        if (isset($this->dataStorageStoreFunctionName[$this->getPaymentMethod()])) {
            return $this->dataStorageStoreFunctionName[$this->getPaymentMethod()];
        }
    }

    public function isSuccessful()
    {
        // Successful if there are no errors.
        // Some payment methods do not require secure storage, so we do not
        // always expect a storageId to be present.

        return ! $this->getErrorCount();
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
     *
     */
    public function getPaymentMethod()
    {
        return $this->getDataValue('paymentMethod');
    }
}
