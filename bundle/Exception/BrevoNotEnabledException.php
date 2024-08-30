<?php

namespace AlmaviaCX\IbexaBrevo\Exception;

use Exception;

class BrevoNotEnabledException extends BrevoException
{
    public function __construct($message = "Brevo is not enabled", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}