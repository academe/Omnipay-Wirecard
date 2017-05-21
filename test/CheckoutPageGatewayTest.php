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
}
