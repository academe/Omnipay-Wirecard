<?php

namespace Omnipay\Wirecard\Message;

/**
 * Payment Response.
 */

use Omnipay\Common\Message\RedirectResponseInterface;

class CheckoutPagePurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * The endpoint.
     */
    protected $endpoint = 'https://checkout.wirecard.com/page/init.php';

    /**
     * The chosen redirect method (POST by default).
     */
    protected $redirectMethod = 'POST';

    /**
     * Not yet "successful" as user needs to be sent to Wirecard site.
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * A redirect goes to the offsite payment page.
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * The method chosen externally.
     */
    public function getRedirectMethod()
    {
        return $this->redirectMethod;
    }

    /**
     * Redirect URL can be long GET or short POST.
     * CHECKME: is GET actually supported?
     */
    public function getRedirectUrl()
    {
        if ($this->getRedirectMethod() == 'GET') {
            return $this->getEndpoint() . '?' . http_build_query($this->getRedirectData(), '', '&');
        } else {
            return $this->getEndpoint();
        }
    }

    /**
     * Data for the URL or form, including the hash.
     */
    public function getRedirectData()
    {
        return $this->getData();
    }

    public function setEndpoint($endpoint)
    {
        return $this->endpoint = $endpoint;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setRedirectMethod($value)
    {
        $this->redirectMethod = $value;
    }
}
