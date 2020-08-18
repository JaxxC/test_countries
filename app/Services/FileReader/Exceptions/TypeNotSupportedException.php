<?php

namespace  App\Services\FileReader\Exceptions;

use Exception;

class TypeNotSupportedException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => 'Type "' . $this->getMessage() . '" not supported'
        ], 400);
    }
}
