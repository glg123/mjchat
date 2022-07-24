<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $fillable = [
        'user_id',
        'post_id',
        'perant_id',
        'text',
        'status',
        'state',
    ];
    protected $appends = ['readed_time'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'perant_id');
    }

    public function post()
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getReadedTimeAttribute()
    {

        return $this->created_at->diffForHumans();

    }

}
