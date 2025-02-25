<?php

namespace App\Exceptions;

use Exception;

class AggregatorException extends Exception
{
    public function __construct($message = "Error in news aggregation", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
