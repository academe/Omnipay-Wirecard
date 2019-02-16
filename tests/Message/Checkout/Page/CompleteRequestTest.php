<?php

namespace Omnipay\Wirecard\Message\Checkout\Page;

use Omnipay\Tests\TestCase;
use Mockery;

class CompleteRequestTest extends TestCase
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
                'omnipayTransactionId' => 'WC92281976',
                'authenticated' => 'No',
                'anonymousPan' => '1003',
                'expiry' => '12/2024',
                'cardholder' => 'asdasdasdsad',
                'maskedPan' => '401200******1003',
                'gatewayReferenceNumber' => 'C729587150057104103101',
                'gatewayContractNumber' => '70003',
                'responseFingerprintOrder' => 'amount,currency,paymentType,financialInstitution,language,orderNumber,paymentState,omnipayTransactionId,authenticated,anonymousPan,expiry,cardholder,maskedPan,gatewayReferenceNumber,gatewayContractNumber,secret,responseFingerprintOrder',
                'responseFingerprint' => '54fc6dd8f29ffc0240db737e275361622d0338ca813e758e0f334e04284c425b05bc17d74d49a2ee5ad69c45dac59432723ec968d1f49c528730fdfb0516b783'
            )
        );

        $request = new CompleteRequest($this->getHttpClient(), $httpRequest);

        // This secret is needed to validate the transaction.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        $request->setTransactionId('WC92281976');

        $response = $request->send();

        // With this data returned with the user, the result should be both valid (the
        // fingerprint) and successful (the paymentState).

        //$this->assertTrue($request->isValid());
        //$this->assertTrue($request->isSuccessful());

        // Sending the request will get the same object back, and so we will have the
        // same success result.

        //var_dump($response->getMessage());
        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccessful());
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
                'omnipayTransactionId' => 'WC96880138',
            )
        );

        $request = new CompleteRequest($this->getHttpClient(), $httpRequest);

        // This secret is needed to validate the transaction.
        // However, the timeout does not have a fingerprint.
        // For CANCEL or FAILURE payment states, there will be no
        // fingerprint, so it will not be checked.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        $request->setTransactionId('WC96880138');

        //$this->assertTrue($request->isValid());
        //$this->assertFalse($request->isSuccessful());

        // Sending the request will get the same object back, and so we will have the
        // same success result.

        $response = $request->send();

        $this->assertTrue($response->isValid());
        $this->assertFalse($response->isSuccessful());
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
                'omnipayTransactionId' => '2147500162',
                'responseFingerprintOrder' => 'paymentType,financialInstitution,language,paymentState,omnipayTransactionId,secret,responseFingerprintOrder',
                'responseFingerprint' => '81bbad473c5ad3b70987ac63b688c823fe9466d1f8f8dda128f44eb9c998badbf1c496d4b136d08b5d79e851024937e5cd3f16e31adf484ce829b5c42780a2f0'
            )
        );

        $request = new CompleteRequest($this->getHttpClient(), $httpRequest);

        $request->setTransactionId('2147500162');

        // This secret is needed to validate the transaction.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        //$this->assertTrue($request->isValid());
        //$this->assertFalse($request->isSuccessful());

        // Sending the request will get the same object back, and so we will have the
        // same success result.

        $response = $request->send();

        //var_dump($response->getMessage());
        $this->assertTrue($response->isValid());
        $this->assertFalse($response->isSuccessful());
    }

    public function invalidFingerPrint()
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
                'omnipayTransactionId' => 'WC92281976',
                'authenticated' => 'No',
                'anonymousPan' => '1003',
                'expiry' => '12/2024',
                'cardholder' => 'asdasdasdsad',
                'maskedPan' => '401200******1003',
                'gatewayReferenceNumber' => 'C729587150057104103101',
                'gatewayContractNumber' => '70003',
                'responseFingerprintOrder' => 'amount,currency,paymentType,financialInstitution,language,orderNumber,paymentState,omnipayTransactionId,authenticated,anonymousPan,expiry,cardholder,maskedPan,gatewayReferenceNumber,gatewayContractNumber,secret,responseFingerprintOrder',
                'responseFingerprint' => '54fc6dd8f29ffc0240db737e275361622d0338ca813e758e0f334e04284c425b05bc17d74d49a2ee5ad69c45dac59432723ec968d1f49c528730fdfb0516b7' . '99' //'83'
            )
        );

        $request = new CompleteRequest($this->getHttpClient(), $httpRequest);

        // This secret is needed to validate the transaction.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        $request->setTransactionId('WC92281976');

        $response = $request->send();

        $this->assertFalse($response->isValid());
        $this->assertFalse($response->isSuccessful());
    }
}
