<?php

namespace Omnipay\Wirecard\Message;

/**
 * Wirecard Seamless Financial Institutions Request.
 */

class BackendSeamlessFinancialInstitutionsRequest extends BackendPageOrderNumberRequest
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
