<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocode extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = [
        'code',
        'promoValue',
        'maxValue',
        'use_counts',
        'valid_from',
        'valid_to',
    ];
    protected $guarded = [];
}
