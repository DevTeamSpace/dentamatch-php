<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model  {
    
    const DEVICE_TYPE_IOS = "ios";
    const DEVICE_TYPE_ANDROID = "android";
    protected $table        = 'devices';
    protected $primaryKey   = 'id';
    
    protected $fillable     = ['id','device_id','user_id','device_token','device_type','device_os','application_version','user_token'];
    

    public static function unRegisterSingle($request){
       Device::where('user_id',$request->input('user_id'))->where('device_id',$request->input('deviceId'))->delete();
       return true;
    }
    public static function unRegisterAll($userId){
       Device::where('user_id',$userId)->delete();
       return true;
    }
    public function registerDevice($deviceId, $userId, $deviceToken, $deviceType,$deviceOs='',$appVersion='') {
        $userToken = bcrypt($deviceId.$userId);
        $data = array(
            'device_id' => $deviceId,
            'user_id' => $userId,
            'device_token' => $deviceToken,
            'device_type' => $deviceType,
            'user_token'=>$userToken,
            'device_os'=>$deviceOs,
            'application_version'=>$appVersion
                );
        $device = Device::firstOrNew(array('device_id' => $deviceId, 'user_id' => $userId));
        $device->fill($data);
        $device->save();
        return $userToken;
    }
    
    public static function getDeviceToken($user_id) {
        
        return Device::where('user_id',$user_id)
                ->join('users','users.id','=','devices.user_id')
                ->where('users.is_active',1)
                ->whereRaw('device_token IS NOT NULL AND device_token!=""')
                ->orderBy('device_id','desc')->first();
    }
    
    public static function getUserByDeviceToken($accessToken) {
        
        return Device::where('user_token',$accessToken)
                ->join('users','users.id','=','devices.user_id')
                ->where('users.is_active',1)
                ->whereRaw('devices.device_token IS NOT NULL AND devices.device_token!=""')
                ->orderBy('devices.id','desc')->first();
    }
    
    public static function getAllDeviceToken($userGroup=1){
        $deviceObj = static::whereRaw('device_token IS NOT NULL AND device_token!=""');
        $deviceObj->join('user_groups','user_groups.user_id','=','devices.user_id');
        if($userGroup=='1'){
            $deviceObj->whereIn('group_id',['2','3']);
        } else {
            $deviceObj->where('group_id',$userGroup);
        }
        return $deviceObj->join('users','users.id','=','devices.user_id')
                ->where('users.is_active',1)
                ->select('devices.user_id','device_token', 'device_type')->distinct('device_token')->get();
    }
    
}

