<?php

namespace App\Exceptions;

use Exception;

class InvalidIdException extends Exception
{
    protected $verb;
    protected $id;

    public function __construct($message, $id)
    {
        parent::__construct($message);
        $this->id = $id;
    }

    public function render($request)
    {
        return response()->json([
            'error' => [
                'message' => $this->message,
                'id' => $this->id
            ]
        ], 404);
    }
}