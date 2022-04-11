<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storyblock extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function owner(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function story(){
        return $this->belongsTo(Post::class,'story_id');
    }
}
