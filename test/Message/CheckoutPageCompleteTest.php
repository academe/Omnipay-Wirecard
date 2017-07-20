<?php

namespace Omnipay\Wirecard\Message;

use Omnipay\Tests\TestCase;
use Mockery;

class CheckoutPageCompleteTest extends TestCase
{
    /**
     *
     */
    public function testTimeout()
    {
        // The incoming server request.
        $httpRequest = $this->getHttpRequest();
        $httpRequest->initialize(
            array(),
            array(
                'amount' => '3.10',
                'currency' => 'EUR',
                'paymentType' => 'CCARD',
                'financialInstitution' => 'Visa',
                'language' => 'en',
                'orderNumber' => '40933885',
                'paymentState' => 'SUCCESS',
                'omnipay_transactionId' => 'WC92281976',
                'authenticated' => 'No',
                'anonymousPan' => '1003',
                'expiry' => '12/2024',
                'cardholder' => 'asdasdasdsad',
                'maskedPan' => '401200******1003',
                'gatewayReferenceNumber' => 'C729587150057104103101',
                'gatewayContractNumber' => '70003',
                'responseFingerprintOrder' => 'amount,currency,paymentType,financialInstitution,language,orderNumber,paymentState,omnipay_transactionId,authenticated,anonymousPan,expiry,cardholder,maskedPan,gatewayReferenceNumber,gatewayContractNumber,secret,responseFingerprintOrder',
                'responseFingerprint' => 'ec3775489f159a1900af1fa2d91a860b2ee1ac3ba5283cb23aee5fe06f1bdc85ab91cac3de145b3f3b2fd1900d50b930f1c708595502da69e4eabb0ec0c1a0f9',
            )
        );

        $request = new CheckoutPageComplete($this->getHttpClient(), $httpRequest);

        // This secret is needed to validate the transaction.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        // With this data returned with the user, the result should be both valid (the
        // fingerprint) and successful (the paymentState).

        $this->assertTrue($request->isValid());
        $this->assertTrue($request->isSuccessful());
    }
}
