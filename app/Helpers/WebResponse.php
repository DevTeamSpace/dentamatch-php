<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class WebResponse
{
    /**
     * @param string $message
     * @param $data
     * @return JsonResponse response
     */
    public static function dataResponse($data = null)
    {
        return self::customResponse(true, null, $data);
    }

    /**
     * @param string $message
     * @param $data
     * @return JsonResponse response
     */
    public static function successResponse($message = null)
    {
        return self::customResponse(true, $message);
    }

    /**
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    public static function errorResponse($message = null)
    {
        return self::customResponse(false, $message);
    }

    /**
     * @param $success
     * @param $message
     * @param $data
     * @return JsonResponse
     */
    public static function customResponse($success, $message = null, $data = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ];
        return response()->json($response);
    }

}

