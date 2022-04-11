<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareStory extends Model
{
    protected $table='shared_stories';
    use HasFactory;
    protected $guarded=[];
}
