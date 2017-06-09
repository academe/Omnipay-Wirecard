<?php

namespace Omnipay\Wirecard\Message;

/**
 * Checkout Page Purchase or Authorize Response.
 */

use Omnipay\Common\Message\RedirectResponseInterface;

class CheckoutPageResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Endpoint for the hosted payment page.
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
     * Redirect URL will be POST.
     */
    public function getRedirectUrl()
    {
        return $this->getEndpoint();
    }

    /**
     * Data for the URL or form, including the hash.
     */
    public function getRedirectData()
    {
        return $this->getData();
    }
}
