<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model  {
    
    const DEVICE_TYPE_IOS = "ios";
    const DEVICE_TYPE_ANDROID = "android";
    protected $table        = 'devices';
    protected $primaryKey   = 'id';
    
    protected $fillable     = ['id','device_id','user_id','device_token','device_type','device_os','application_version','user_token'];
    

    public static function unRegister_single($request){
       Device::where('user_id',$request->input('user_id'))->where('device_id',$request->input('deviceId'))->delete();
       return true;
    }
    public static function unRegister_all($user_id){
       Device::where('user_id',$user_id)->delete();
       return true;
    }
    public function register_device($device_id, $user_id, $device_token, $device_type,$deviceOs='',$appVersion='') {
        $user_token = md5($device_id.$user_id);
        $data = array(
            'device_id' => $device_id,
            'user_id' => $user_id,
            'device_token' => $device_token,
            'device_type' => $device_type,
            'user_token'=>$user_token,
            'device_os'=>$deviceOs,
            'application_version'=>$appVersion
                );
        $device = Device::firstOrNew(array('device_id' => $device_id, 'user_id' => $user_id));
        $device->fill($data);
        $device->save();
        return $user_token;
    }
    
    public static function getDeviceToken($user_id) {
        
        return Device::where('user_id',$user_id)
                ->join('users','users.id','=','devices.user_id')
                ->where('users.is_active',1)
                ->whereRaw('device_token IS NOT NULL AND device_token!=""')
                ->orderBy('dev_id','desc')->first();
    }
    
    public static function getUserByDeviceToken($accessToken) {
        
        return Device::where('user_token',$accessToken)
                ->join('users','users.id','=','devices.user_id')
                ->where('users.is_active',1)
                ->whereRaw('devices.device_token IS NOT NULL AND devices.device_token!=""')
                ->orderBy('devices.id','desc')->first();
    }
    
    public static function getAllDeviceToken($userGroup=1,$messageType='2'){
        $deviceObj = static::whereRaw('device_token IS NOT NULL AND device_token!=""');
        if($messageType=='1'){
            if($userGroup=='1'){
                $deviceObj->join('events',function($join){
                        $join->on('events.consumer_id','=','devices.user_id');
                        $join->orOn('events.photographer_id','=','devices.user_id');
                    });
            }else{
                $column = ($userGroup=='2')?'photographer_id':'consumer_id';
                $deviceObj->join('events','events.'.$column,'=','devices.user_id');
            }
            $deviceObj->whereRaw('users.created_at <= DATE_SUB(CURDATE(),INTERVAL 30 DAY)');
        }else{
            $deviceObj->join('user_groups','user_groups.user_id','=','devices.user_id');
            if($userGroup=='1'){
                $deviceObj->whereIn('group_id',['2','3']);
            }else{
                $deviceObj->where('group_id',$userGroup);
            }
        }
        return $deviceObj->join('users','users.id','=','devices.user_id')
                ->where('users.is_active',1)
                ->select('devices.user_id','device_token')->distinct('device_token')->get();
    }
    // clear badge method
    
}

