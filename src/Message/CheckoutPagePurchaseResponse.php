<?php

namespace Omnipay\Wirecard\Message;

/**
 * Payment Response.
 */

use Omnipay\Common\Message\RedirectResponseInterface;

class CheckoutPagePurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Endpoint location of the hosted payment page.
     */
    protected $endpoint = 'https://checkout.wirecard.com/page/init.php';

    /**
     * The chosen redirect method (POST by default).
     */
    protected $redirectMethod = 'POST';

    /**
     * Not yet "successful" as user needs to be sent to Wirecard site.
     * TODO: this may be true.
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * A redirect goes to the offsite payment page.
     * TODO: this may not always be a redirect.
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
     * TODO: not a fixed URL, but one that the gateway may have passed back.
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
