<?php

namespace Omnipay\Wirecard\Message\Checkout\Seamless;

/**
 * Wirecard Seamless Abstract Request.
 * The Seamless API handles some response data differently to the Page API.
 */

use Omnipay\Wirecard\Message\AbstractResponse as MessageAbstractResponse;
use Omnipay\Wirecard\Message\HasDataTrait;

abstract class AbstractResponse extends MessageAbstractResponse
{
    // Helper functions for accessing the data values
    use HasDataTrait;

    /**
     * The count of error messages.
     *
     * @return int The number of errors, zero if there are no errors.
     */
    public function getErrorCount()
    {
        return (int)$this->getDataValue('errors', 0);
    }

    /**
     * The array of error messages.
     *
     * @return array The array of error messages.
     */
    public function getErrors()
    {
        $errors = [];

        if ($error_count = $this->getErrorCount()) {
            // The documentation lists field names using full stops as part separators.
            // The demo site uses underscores as part separators.
            // We will use them interchangeably, trying underscores first.

            for ($i = 1; $i <= $error_count; $i++) {
                $error = [];

                foreach (['errorCode', 'message', 'consumerMessage', 'paySysMessage'] as $part) {
                    $error[$part] = $this->getDataValue(
                        'error_' . $i . '_' . $part,
                        $this->getDataValue('error.' . $i . '.' . $part)
                    );
                }

                $errors[] = $error;
            }
        }

        return $errors;
    }
}
