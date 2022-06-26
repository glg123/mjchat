<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryView extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $fillable=[
        'story_id',
        'user_id',
        'status',
    ];
}
