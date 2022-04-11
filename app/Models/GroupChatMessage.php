<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChatMessage extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function getMsgAttribute($value)
    {

        if ($this->type == 'media') {
            return url($value);
        }
        else
        {
            return $value;
        }
    }
}
