<?php

namespace Omnipay\Wirecard\Message\Backend\Page;

use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new CaptureRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(
            [
                'amount' => '0.50',
                'currency' => 'EUR',
                'transactionReference' => '123456',
                'language' => 'en',
            ]
        );
    }

    /**
     * A placeholder for some tests, when I have time...
     */
    public function testSimple()
    {
        $this->assertSame(
            [
                'customerId' => null,
                'toolkitPassword' => null,
                'command' => 'deposit',
                'language' => 'en',
                'orderNumber' => '123456',
                'amount' => '0.50',
                'currency' => 'EUR',
                'requestFingerprint' => '5674126ac05d7b661f0d4436842160e6ba2c3cce47c4084d041cc8069b8021292970b735f7d42ece80255cfb73bfebeea6d3605e99481665a3c5bca1bb0896c8',
            ],
            $this->request->getData()
        );
    }

    public function testFingerprintOrder()
    {
        $data = [
            'foo' => '123',
            'bar' => '456',
        ];

        $this->assertSame(
            $this->request->getRequestFingerprintOrder($data),
            'foo,bar,requestFingerprintOrder,secret'
        );

        $this->assertSame(
            $this->request->getRequestFingerprint($data),
            'd8f16c86e186a302063a60e27d0d31d67b0b70b0a495da650b27eb96080a598edcee1230cf2a31df9ca33495ea7ccb2ef8cb5af43ea2f60bcc39d623baf8da6b'
        );
    }
}
