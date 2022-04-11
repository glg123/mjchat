<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Messages extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $fillable = [
        'content',
        'message_reasons_id',
        'status',
        'email',
    ];
    protected $appends = ['message_reason_name'];

    public function message_reasons()
    {
        return $this->belongsTo(MessageReson::class, 'message_reasons_id', 'id');
    }

    public function getMessageReasonNameAttribute(): string
    {

        return @$this->message_reasons->name;

    }
}
