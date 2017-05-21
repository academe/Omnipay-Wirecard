<?php

namespace Omnipay\Wirecard\Message;

use Omnipay\Tests\TestCase;

class CheckoutPageAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new CheckoutPageAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());

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
