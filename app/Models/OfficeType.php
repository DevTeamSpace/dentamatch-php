<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\OfficeType
 *
 * @property int $id
 * @property string $officetype_name
 * @property int $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|OfficeType newModelQuery()
 * @method static Builder|OfficeType newQuery()
 * @method static Builder|OfficeType query()
 * @method static Builder|OfficeType whereCreatedAt($value)
 * @method static Builder|OfficeType whereId($value)
 * @method static Builder|OfficeType whereIsActive($value)
 * @method static Builder|OfficeType whereOfficetypeName($value)
 * @method static Builder|OfficeType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfficeType extends Model
{
    protected $table = 'office_types';

    protected $fillable = ['officetype_name', 'is_acitve'];

    public static function allOfficeTypes()
    {
        return OfficeType::where('is_active', 1)->get();
    }

}
