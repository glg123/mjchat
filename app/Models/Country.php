<?php

namespace App\Models;

use App\Traits\NameTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;
    use NameTrait;
    use SoftDeletes;
    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'logo',
        'status',
    ];
    protected $guarded=[];
    protected $appends = ['name'];
    protected $hidden=['name_ar','name_en'];
    public function getLogoAttribute($value)
    {

        if ($value) {


            return \Storage::disk('countries')->url($value);
        }
        return \Storage::disk('users')->url('');

    }
}
