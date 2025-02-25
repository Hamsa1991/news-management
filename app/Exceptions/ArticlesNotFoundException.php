<?php

namespace App\Exceptions;

use Exception;

class ArticlesNotFoundException extends Exception
{
    public function __construct($message = "Articles not found", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
