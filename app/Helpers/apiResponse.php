<?php
namespace App\Helpers;
use App\Models\Device;

class apiResponse {

    public static function responseError($message = '', $data = array()) {
        $key = !empty($data) ? key($data) : '';
        $response = array(
            'status' => 0,
            'message' => $message
        );
        if (!empty($key)) {
            $response[$key] = (object) $data[$key];
        }
        return self::convertToCamelCase($response);
    }
    
    public static function convertToCamelCase($array) {
        $converted_array = [];
        foreach ($array as $old_key => $value) {
            if (is_array($value)) {
                $value = self::convertToCamelCase($value);
            } else if (is_object($value)) {
                if (method_exists($value, 'toArray')) {
                    $value = $value->toArray();
                } else {
                    $value = (array) $value;
                }


                $value = self::convertToCamelCase($value);
            }
            $converted_array[camel_case($old_key)] = $value;
        }

        return $converted_array;
    }
    
    public static function customJsonResponse($status, $statusCode, $message = '', $data = array()) {
        $response = array(
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        );
        if(is_array($data) && count($data) > 0){
            $response['result'] = (object) $data;
        }
        return json_encode($response);
    }
    
    public static function customJsonResponseObject($status, $statusCode, $message = '',$key = '', $obj = null) {
        $response = array(
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        );
        if(!is_null($obj) && $key != ""){
            $response['result'][$key] = $obj;
        }
        return json_encode($response);
    }
    
    public static function loginUserId($accesstoken) {
        $user = Device::select('user_id')->where('user_token',$accesstoken)->first();
        if($user){
            return $user->user_id;
        }else{
            return 0;
        }
    }
}

