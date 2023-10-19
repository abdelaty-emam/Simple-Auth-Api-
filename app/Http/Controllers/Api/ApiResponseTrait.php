<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{
    public function coreResponse($message, $data = null, $statusCode, $isSuccess = true)
    {
        // Check the params
        if (!$message) return response()->json(['message' => 'Message is required'], 500);

        // Send the response
        if ($isSuccess) {
            return response()->json([
                'message' => $message,
                'error' => false,
                'code' => $statusCode,
                'data' => $data
            ], $statusCode);
        } else {
            return response()->json([
                'message' => $message,
                'error' => true,
                'code' => $statusCode,
            ], $statusCode);
        }
    }

    public function success($message, $data = null, $statusCode = 200)
    {
        return $this->coreResponse($message, $data, $statusCode);
    }

    public function error($message, $statusCode = 500)
    {
        return $this->coreResponse($message, null, $statusCode, false);
    }
}
