<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionPost extends Model
{

    protected $table = 'promotion_posts';
    use SoftDeletes;

    protected $fillable = [
        'promotion_id',
        'post_id',
        'start_date',
        'end_date',
        'status',
        'count_views',
        'remaining_views',
        'order_id',
        'order_status',
        'order_create_at',
        'order_updated_at',
        'total_price',
        'currency_code',
        'payer_name',
        'payer_email_address',
        'payer_id',


    ];


}
