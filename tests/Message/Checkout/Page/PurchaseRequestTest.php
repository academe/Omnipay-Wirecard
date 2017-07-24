<?php

namespace Omnipay\Wirecard\Message\Checkout\Page;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(
            array(
                'amount' => '12.00',
                'currency' => 'GBP',
                'card' => $this->getValidCard(),
            )
        );
    }

    /**
     * A placeholder for some tests, when I have time...
     */
    public function testNoop()
    {
        $this->assertSame(true, true);
    }
}
