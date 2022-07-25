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
    protected $appends = ['promotion_package_name', 'user_name', 'post_name'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function PromotionPackage()
    {
        return $this->belongsTo(PromotionPackage::class, 'promotion_id', 'id');
    }

    public function getPromotionPackageNameAttribute()
    {

        $lang = app()->getLocale();
        $name = 'title_' . $lang;
        return @$this->PromotionPackage->$name;

    }

    public function getUserNameAttribute()
    {


        return @$this->user->first_name . ' ' . @$this->user->last_name;

    }

    public function getPostNameAttribute()
    {


        return @$this->post->comment;

    }

}
