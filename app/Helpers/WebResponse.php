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

    public static function csvResponse($data, $csvHeaders, $fileName) {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName ."_" . time() . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $csvHeaders = array_map(function ($title) {
            return ucwords(str_replace('_', ' ', $title));
        }, $csvHeaders);

        $callback = function() use ($data, $csvHeaders)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeaders);

            foreach ($data as $value) {
                fputcsv($file, $value);
            }

            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

}

