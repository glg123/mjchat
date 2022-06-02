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
        'title_en',
        'type',
        'description_ar',
        'description_en',
        'description_en',
        'count_post',
        'count_views',
        'count_days',
        'price',
        'total_price',
        'status',
    ];


}
