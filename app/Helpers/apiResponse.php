<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('apiResponse')) {
    function apiResponse(
        bool $status,
        string $message,
        $data,
        int $httpCode
    ): JsonResponse {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ], $httpCode);
    }
}
