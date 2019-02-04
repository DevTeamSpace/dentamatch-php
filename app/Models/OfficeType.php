<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OfficeType
 *
 * @property int $id
 * @property string $officetype_name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OfficeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OfficeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OfficeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OfficeType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OfficeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OfficeType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OfficeType whereOfficetypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OfficeType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfficeType extends Model {

    protected $table = 'office_types';
    protected $primaryKey = 'id';
    protected $fillable = ['officetype_name', 'is_acitve'];
    
    public static function allOfficeTypes(){
        return OfficeType::where('is_active',1)->get();
    }

}
