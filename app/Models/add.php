<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Malhal\Geographical\Geographical;

class add extends Model
{
    use HasFactory;
    use Geographical;
    use SoftDeletes;
    protected $guarded=[];
    const LATITUDE  = 'lat';
    const LONGITUDE = 'long';
    protected static $kilometers = true;


    public function getImgAttribute($value)
    {

        if ($value) {


            return \Storage::disk('adds')->url($value);
        }
        return \Storage::disk('adds')->url('');

    }

}
