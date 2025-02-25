<?php

namespace App\Exceptions;

use Exception;

class TransformationException extends Exception
{
    public function __construct($message = "Error transforming news data", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
