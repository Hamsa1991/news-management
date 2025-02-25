<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message = 'User does not exist.';

    public function render()
    {
        return response()->json(['error' => $this->message], 500);
    }
}
