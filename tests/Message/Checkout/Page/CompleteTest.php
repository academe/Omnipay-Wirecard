<?php

namespace Omnipay\Wirecard\Message\Checkout\Page;

use Omnipay\Tests\TestCase;
use Mockery;

class CompleteTest extends TestCase
{
    /**
     *
     */
    public function testSuccesful()
    {
        // The incoming server request.
        // I hope this request object is not intialising itself from local
        // globals, because that could make the test a little sensitive to
        // the environment.

        $httpRequest = $this->getHttpRequest();

        $httpRequest->initialize(
            array(), // GET
            array( // POST
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

        $request = new Complete($this->getHttpClient(), $httpRequest);

        // This secret is needed to validate the transaction.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        // With this data returned with the user, the result should be both valid (the
        // fingerprint) and successful (the paymentState).

        $this->assertTrue($request->isValid());
        $this->assertTrue($request->isSuccessful());

        // Sending the request will get the same object back, and so we will have the
        // same success result.

        $response = $request->send();

        $this->assertTrue($request->isValid());
        $this->assertTrue($request->isSuccessful());
    }

    /**
     * TODO: need some examples of failed transactions and *timed out* transactions
     * which have been reported as having problems.
     */
    public function testTimeout()
    {
        $httpRequest = $this->getHttpRequest();

        $httpRequest->initialize(
            array(), // GET
            array( // POST
                'consumerMessage' => 'QPAY-Session timed out after 30 minutes without activity.',
                'message' => 'QPAY-Session timed out after 30 minutes without activity.',
                'paymentState' => 'FAILURE',
                'omnipay_transactionId' => 'WC96880138',
            )
        );

        $request = new Complete($this->getHttpClient(), $httpRequest);

        // This secret is needed to validate the transaction.
        // However, the timeout does not have a fingerprint.
        // For CANCEL or FAILURE payment states, there will be no
        // fingerprint, so it will not be checked.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        $this->assertTrue($request->isValid());
        $this->assertFalse($request->isSuccessful());

        // Sending the request will get the same object back, and so we will have the
        // same success result.

        $response = $request->send();

        $this->assertTrue($request->isValid());
        $this->assertFalse($request->isSuccessful());
    }

    public function testPending()
    {
        $httpRequest = $this->getHttpRequest();

        $httpRequest->initialize(
            array(), // GET
            array( // POST
                'paymentType' => 'PAYPAL',
                'financialInstitution' => 'PayPal',
                'language' => 'de',
                'paymentState' => 'PENDING',
                'omnipay_transactionId' => '2147500162',
                'responseFingerprintOrder' => 'paymentType,financialInstitution,language,paymentState,omnipay_transactionId,secret,responseFingerprintOrder',
                'responseFingerprint' => 'ebf04ba2e87dd12c03eb889e523e453ae1db8ffd9fd3b5b6ca7c9f5c61763afc28f3eb318c009e0e193b2d3f5b655e3333247094ee86c0d531235f4661b19a51',
            )
        );

        $request = new Complete($this->getHttpClient(), $httpRequest);

        // This secret is needed to validate the transaction.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        $this->assertTrue($request->isValid());
        $this->assertFalse($request->isSuccessful());

        // Sending the request will get the same object back, and so we will have the
        // same success result.

        $response = $request->send();

        $this->assertTrue($request->isValid());
        $this->assertFalse($request->isSuccessful());
    }
}
