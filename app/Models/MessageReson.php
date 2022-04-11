<?php

namespace App\Models;


use App\Traits\NameTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageReson extends Model
{
    use HasFactory;
    use NameTrait;
    use SoftDeletes;

    protected $appends = ['name'];
    protected $guarded = [];
    protected $hidden = ['name_ar', 'name_en'];



}
