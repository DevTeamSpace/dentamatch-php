<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Device
 *
 * @property int $id
 * @property int $user_id
 * @property string $device_type
 * @property string $device_id
 * @property string $user_token
 * @property string|null $ip_address
 * @property string $device_os
 * @property string $application_version
 * @property string $device_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static Builder|Device newModelQuery()
 * @method static Builder|Device newQuery()
 * @method static Builder|Device query()
 * @method static Builder|Device whereApplicationVersion($value)
 * @method static Builder|Device whereCreatedAt($value)
 * @method static Builder|Device whereDeviceId($value)
 * @method static Builder|Device whereDeviceOs($value)
 * @method static Builder|Device whereDeviceToken($value)
 * @method static Builder|Device whereDeviceType($value)
 * @method static Builder|Device whereId($value)
 * @method static Builder|Device whereIpAddress($value)
 * @method static Builder|Device whereUpdatedAt($value)
 * @method static Builder|Device whereUserId($value)
 * @method static Builder|Device whereUserToken($value)
 * @mixin \Eloquent
 */
class Device extends Model
{

    const DEVICE_TYPE_IOS = "ios";
    const DEVICE_TYPE_ANDROID = "android";
    protected $table = 'devices';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'device_id', 'user_id', 'device_token', 'device_type', 'device_os', 'application_version', 'user_token'];


    public static function unRegisterSingle($request)
    {
        Device::where('user_id', $request->input('user_id'))->where('device_id', $request->input('deviceId'))->delete();
        return true;
    }

    public static function unRegisterAll($userId)
    {
        Device::where('user_id', $userId)->delete();
        return true;
    }

    public function registerDevice($deviceId, $userId, $deviceToken, $deviceType, $deviceOs = '', $appVersion = '')
    {
        $userToken = bcrypt($deviceId . $userId);
        $data = [
            'device_id'           => $deviceId,
            'user_id'             => $userId,
            'device_token'        => $deviceToken,
            'device_type'         => $deviceType,
            'user_token'          => $userToken,
            'device_os'           => $deviceOs,
            'application_version' => $appVersion
        ];
        $device = Device::firstOrNew(['device_id' => $deviceId, 'user_id' => $userId]);
        $device->fill($data);
        $device->save();
        return $userToken;
    }

    public static function getDeviceToken($user_id)
    {
        return Device::where('user_id', $user_id)
            ->join('users', 'users.id', '=', 'devices.user_id')
            ->where('users.is_active', 1)
            ->whereRaw('device_token IS NOT NULL AND device_token!=""')
            ->orderBy('device_id', 'desc')->first();
    }

    public static function getUserByDeviceToken($accessToken)
    {
        return Device::where('user_token', $accessToken)
            ->join('users', 'users.id', '=', 'devices.user_id')
            ->where('users.is_active', 1)
            ->whereRaw('devices.device_token IS NOT NULL AND devices.device_token!=""')
            ->orderBy('devices.id', 'desc')->first();
    }

    public static function getAllSeekersDeviceToken()
    {
        $deviceObj = static::whereRaw('device_token IS NOT NULL AND device_token!=""');
        $deviceObj->join('user_groups', 'user_groups.user_id', '=', 'devices.user_id')
            ->where('group_id', UserGroup::JOBSEEKER);

        return $deviceObj->join('users', 'users.id', '=', 'devices.user_id')
            ->where('users.is_active', 1)
            ->select('devices.user_id', 'device_token', 'device_type')->distinct('device_token')->get();
    }

}

