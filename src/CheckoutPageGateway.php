<?php

namespace Omnipay\Wirecard;

/**
 * Wirecard Checkout Page driver for Omnipay
 */

use Omnipay\Common\Exception\InvalidRequestException;

class CheckoutPageGateway extends AbstractGateway
{
    /**
     * The default server endpoint.
     */
    protected $endpoint = 'https://checkout.wirecard.com/page/init.php';

    /**
     * The common name for this gateway driver API.
     */
    public function getName()
    {
        return 'Wirecard Checkout Page Client';
    }

    /**
     * 
     */
    public function getDefaultParameters()
    {
        $params = parent::getDefaultParameters();

        return $params;
    }

    /**
     * The authorization transaction.
     */
//    public function authorize(array $parameters = array())
//    {
//        return $this->createRequest('\Omnipay\Wirecard\Message\XxxAuthorizeRequest', $parameters);
//    }

    /**
     * The purchase transaction.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutPagePurchaseRequest', $parameters);
    }

    /**
     * The complete authorization transaction (capturing data retuned with the user).
     */
    //public function completeAuthorize(array $parameters = array())
    //{
    //    return $this->createRequest('\Omnipay\Wirecard\Message\XxxCompleteAuthorizeRequest', $parameters);
    //}

    /**
     * The complete purchase transaction (capturing data retuned with the user).
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutPageCompletePurchase', $parameters);
    }

    /**
     * Accept an incoming notification (a ServerRequest).
     */
    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\NotificationServer', $parameters);
    }
}
