<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userblock extends Model
{


    protected $fillable=[
        'block_user_id',
        'user_id',
        'status',
    ];
    use HasFactory;
    protected $guarded=[];
    public function owner(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'block_user_id');
    }
}
