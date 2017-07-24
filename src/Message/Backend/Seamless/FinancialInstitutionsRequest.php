<?php

namespace Omnipay\Wirecard\Message\Backend\Seamless;

/**
 * Wirecard Seamless Financial Institutions Request.
 */

use Omnipay\Wirecard\Message\Backend\Page\OrderNumberRequest as PageOrderNumberRequest;

class FinancialInstitutionsRequest extends PageOrderNumberRequest
{
    /**
     * Seamless uses the URL in place of a command parameter.
     */
    protected $endpoint = 'https://checkout.wirecard.com/seamless/backend/getFinancialInstitutions';

    /**
     * No command for seamless; it's all in the URL.
     */
    protected $command = '';
}
