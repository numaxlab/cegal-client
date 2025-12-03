<?php

namespace NumaxLab\Cegal\Exceptions;

use RuntimeException;

class CegalApiException extends RuntimeException
{
    public function __construct(int $code = 0)
    {
        $message = match ($code) {
            0 => 'Internal error',
            1 => 'Access denied',
            2 => 'Invalid credentials',
            3 => 'Insufficient credit',
            4 => 'Invalid ISBN',
            5 => 'ISBN not found',
            6 => 'Not specified',
            7 => 'Invalid argument',
            8 => 'No results for the requested ranking',
            default => 'Unknown error'
        };

        parent::__construct($message, $code);
    }
}
