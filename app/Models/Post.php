<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Malhal\Geographical\Geographical;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    use Geographical;

    const LATITUDE = 'lat';
    const LONGITUDE = 'long';
    protected static $kilometers = true;
    protected $fillable = [
        'user_id',
        'comment',
        'media',
        'lat',
        'long',
        'status',
        'recommended',
        'type',
        'has_comments',
        'peremotion_type',
        'shares',
        'count_likes',
        'count_comments',
        'views',
        'shared',
        'start_date',
        'expire_date',
    ];
protected $appends=['user_name'];
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getMediaAttribute($value)
    {

        if ($value) {


            return \Storage::disk('posts')->url($value);
        }
        return \Storage::disk('posts')->url('');

    }
    public function comments(){
        return $this->hasMany(Comment::class,'post_id');
    }

    public function PromotionPost(){
        return $this->hasOne(PromotionPost::class,'post_id');
    }
    public function getUserNameAttribute()
    {

     return @$this->owner->first_name.' '.@$this->owner->last_name;

    }

}
