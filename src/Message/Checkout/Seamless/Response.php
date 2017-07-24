<?php

namespace Omnipay\Wirecard\Message\Checkout\Seamless;

/**
 * Checkout Seemless Purchase or Authorize Response.
 * The response will be either a rediect URL or a list of errors.
 */

use Omnipay\Common\Message\RedirectResponseInterface;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * The chosen redirect method (POST by default).
     */
    protected $redirectMethod = 'GET';

    /**
     * Not yet successful, because a redirect is needed to complete
     * the transaction.
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Redirect URL is a simple GET.
     */
    public function getRedirectUrl()
    {
        return $this->getDataValue('redirectUrl');
    }

    /**
     * The method chosen externally.
     */
    public function getRedirectMethod()
    {
        return $this->redirectMethod;
    }

    /**
     * There is no additional data to redirect with.
     */
    public function getRedirectData()
    {
        return [];
    }

    /**
     * Will be a redierect if there is a redirect URL.
     */
    public function isRedirect()
    {
        return $this->getRedirectUrl() !== null;
    }
}
