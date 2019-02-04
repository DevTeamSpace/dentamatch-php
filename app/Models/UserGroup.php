<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserGroup
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereUserId($value)
 * @mixin \Eloquent
 */
class UserGroup extends Model {
    
    const ADMIN = 1;
    const RECRUITER = 2;
    const JOBSEEKER = 3;

    protected $table = 'user_groups';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'group_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
