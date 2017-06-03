<?php

namespace Omnipay\Wirecard;

/**
 * Wirecard Checkout Seamless driver for Omnipay
 */

use Omnipay\Wirecard\Traits\CheckoutSeamlessParametersTrait;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Wirecard\Traits\CheckoutParametersTrait;

class CheckoutSeamlessGateway extends AbstractGateway
{
    // Custom parameters implemented for the Checkout APIs.
    use CheckoutParametersTrait;

    // Custom parameters implemented for the Checkout Seamless API.
    use CheckoutSeamlessParametersTrait;

    /**
     * The common name for this gateway driver API.
     */
    public function getName()
    {
        return 'Wirecard Checkout Seamless Client';
    }

    /**
     * 
     */
    public function getDefaultParameters()
    {
        $params = parent::getDefaultParameters();

        // Parameters for Checkout Page and Checkout Seamless APIs.
        // TODO: move these to an AbstractCheckoutGateway class once
        // Seamless is supported.
        /*
        $params['noScriptInfoUrl'] = '';
        $params['windowName'] = '';
        $params['duplicateRequestCheck'] = true;
        $params['transactionIdentifier'] = '';
        $params['financialInstitution'] = '';
        $params['cssUrl'] = '';
        */

        return $params;
    }

    /**
     * The authorization transaction.
     */
//    public function authorize(array $parameters = array())
//    {
//        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutSeamlessAuthorizeRequest', $parameters);
//    }

    /**
     * The purchase transaction.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutSeamlessPurchaseRequest', $parameters);
    }

    /**
     * The complete authorization transaction (capturing data retuned with the user).
     */
//    public function completeAuthorize(array $parameters = array())
//    {
//        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutSeamlessComplete', $parameters);
//    }

    /**
     * The complete purchase transaction (capturing data retuned with the user).
     */
//    public function completePurchase(array $parameters = array())
///    {
//        return $this->createRequest('\Omnipay\Wirecard\Message\CheckoutPageComplete', $parameters);
//    }

    /**
     * The capture transaction.
     */
//    public function capture(array $parameters = array())
//    {
//        return $this->createRequest('\Omnipay\Wirecard\Message\BackendCaptureRequest', $parameters);
//    }

    /**
     * The refund transaction.
     */
//    public function refund(array $parameters = array())
//    {
//        return $this->createRequest('\Omnipay\Wirecard\Message\BackendRefundRequest', $parameters);
//    }

    /**
     * Accept an incoming notification (a ServerRequest).
     */
//    public function acceptNotification(array $parameters = array())
//    {
//        return $this->createRequest('\Omnipay\Wirecard\Message\NotificationServer', $parameters);
//    }
}
