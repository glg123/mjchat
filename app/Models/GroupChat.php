<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function members(){
        return $this->belongsToMany(User::class,'group_chat_members','group_id','user_id');
    }

    public function msgs(){
        return $this->hasMany(GroupChatMessage::class,'group_id');
    }

    public function onwer(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
