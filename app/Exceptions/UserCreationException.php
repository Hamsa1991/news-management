<?php

namespace App\Exceptions;

use Exception;

class UserCreationException extends Exception
{
    protected $message = 'User creation failed.';

    public function render()
    {
        return response()->json(['error' => $this->message], 500);
    }
}
