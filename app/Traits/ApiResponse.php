<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($code = 200, $message = null, $data = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($code, $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    protected function missingFieldResponse(string $field)
    {
        return $this->errorResponse(400, "Field '" . $field . "' is missing");
    }
}
