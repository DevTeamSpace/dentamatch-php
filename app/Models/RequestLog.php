<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RequestLog
 *
 * @property int $id
 * @property string $path
 * @property string $ip
 * @property string $request
 * @property string $response
 * @property int|null $user_id
 * @property int $duration
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @mixin \Eloquent
 */
class RequestLog extends Model
{
    protected $table = 'request_log';
    protected $primaryKey = 'id';

    protected $fillable = [];

}
