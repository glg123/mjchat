<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveStory extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'fav_stories';
    protected $fillable =
        [
            'user_id',
            'story_id',
            'status',
        ];
}
