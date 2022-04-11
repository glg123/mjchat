<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $fillable = [
        'content',
        'post_id',
        'user_id',
        'message_reasons_id',
        'status',

    ];
    protected $appends = ['message_reason_name'];

    public function message_reasons()
    {
        return $this->belongsTo(MessageReson::class, 'message_reasons_id', 'id');
    }
    public function post()
    {
        return $this->hasOne(Post::class, 'id','post_id' );
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function getMessageReasonNameAttribute(): string
    {

        return @$this->message_reasons->name;

    }
}
