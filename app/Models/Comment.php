<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded=[];
    protected $fillable = [
        'user_id',
        'post_id',
        'perant_id',
        'text',
        'status',
        'state',
    ];
    public function comments(){
        return $this->hasMany(Comment::class,'perant_id');
    }

}
