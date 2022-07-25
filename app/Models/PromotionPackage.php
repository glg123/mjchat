<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionPackage extends Model
{

    protected $table = 'promotion_packges';
    use SoftDeletes;

    protected $fillable = [
        'title_ar',
        'title_en',
        'type',
        'description_ar',
        'description_en',
        'description_en',
        'count_views',
        'count_days',
        'price',
        'total_price',
        'status',
    ];

    protected $appends = ['title','description'];

    public function getTitleAttribute()
    {

        $lang = app()->getLocale();
        $name = 'title_' . $lang;
        return @$this->$name;

    }
    public function getDescriptionAttribute()
    {

        $lang = app()->getLocale();
        $name = 'description_' . $lang;
        return @$this->$name;

    }
}
