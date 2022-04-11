<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotifaction extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'user_id',
        'post_id',
        'status',
        'type',
    ];

    public function post()
    {
        return $this->hasOne(Post::class,'id','post_id');
    }
}
