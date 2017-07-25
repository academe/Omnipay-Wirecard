<?php

namespace Omnipay\Wirecard;

use Omnipay\Tests\GatewayTestCase;

class CheckoutPageGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new CheckoutPageGateway($this->getHttpClient(), $this->getHttpRequest());

        // There auth details are the "demo mode" details and can be used against the live gateway.
        $this->options = [
            'customerId' => 'D200001',
            'shopId' => null,
            'secret' => 'B8AKTPWBRMNBV455FG6M2DANE99WU2',
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        ];

        $this->purchaseOptions = [
            'amount' => '10.00',
            'transactionId' => '123',
            'card' => $this->getValidCard(),
        ];

        $this->captureOptions = [
            'amount' => '10.00',
            'transactionId' => '123',
            'transactionReference' => '????',
        ];
    }

    /**
     * Make sure we can instantiate each type of request.
     */
    public function testInstantiateRequest()
    {
        $this->gateway->initialize($this->options);

        $request = $this->gateway->authorize();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->purchase();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->completeAuthorize();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->completePurchase();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->capture();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->voidCapture();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->voidAuthorize();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->refund();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->voidRefund();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->void();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->acceptNotification();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->createOrderNumber();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->createTransactionReference();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->getFinancialInstitutions();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->fetchTransaction();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->recurPurchase();
        $this->assertSame('D200001', $request->getCustomerId());

        $request = $this->gateway->recurAuthorize();
        $this->assertSame('D200001', $request->getCustomerId());
    }
}
