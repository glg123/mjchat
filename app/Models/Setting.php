<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Self_;

/**
 * Class Setting
 *
 * @package App\Models
 * @SWG\Definition(type="object")
 */
class Setting extends Model
{
    /**
     * @SWG\Property(
     *   property="id",
     *   type="integer",
     * )
     * @SWG\Property(
     *   property="value_ar",
     *   type="string",
     * )
     * @SWG\Property(
     *   property="value_en",
     *   type="string",
     * )
     * @SWG\Property(
     *   property="key",
     *   type="string",
     * )
     * @SWG\Property(
     *   property="status",
     *   type="string",
     * )
     */

    use SoftDeletes;

    /**
     * The attributes that are guarded from  mass assignable.
     *
     * @var array
     */


    protected $guarded = [

    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value_ar',
        'value_en',
        'key',
        'status',


    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];
protected $appends=['value'];
    public function getValueAttribute()
    {
        $local = (app('request')->hasHeader('language')) ? app('request')->header('language') : 'ar';
        $name = 'value' . '_'.$local;
        return $this->$name;

    }


}
