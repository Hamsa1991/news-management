<?php

namespace App\Exceptions;

use Exception;

class TransientErrorException extends Exception
{
    public function __construct($message = "Transient error occurred during fetching.", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
