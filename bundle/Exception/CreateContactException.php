<?php

namespace AlmaviaCX\IbexaBrevo\Exception;

use Exception;

class CreateContactException extends BrevoException
{
    public function __construct($message = "", $code = 0, Exception $previous = null, protected ?array $data = null)
    {
        parent::__construct($message, $code, $previous);
    }
}