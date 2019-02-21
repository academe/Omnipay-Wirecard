<?php

namespace Omnipay\Wirecard\Message;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Wirecard\Message\Checkout\Page\CompleteRequest;
use Omnipay\Wirecard\CommonParametersTrait;
use Omnipay\Tests\TestCase;

class NotificationServerTest extends TestCase
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

        $request = new NotificationServer($this->getHttpClient(), $httpRequest);

        // This secret is needed to validate the transaction.
        $request->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');

        // Will be successful and valid even without an expected transactionId set
        // since this is an unsolicited notification and not a complete* message.

        $this->assertTrue($request->isValid());
        $this->assertTrue($request->isSuccessful());

        $this->assertSame('WC92281976', $request->getTransactionId());
    }
}
