<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class Admin
 *
 * @package App\Models
 * @SWG\Definition(type="object")
 */
class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',

        'password',
        'device_token',
        'device_type',
        'mobile',

        'country_code',
        'confirmation_code',
        'logo',

        'confirmation_password_code',
        'status',
        'mobile_verified_at',
        'email_verified_at',
        'country_code',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    //   protected $appends = ['api_token'];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];





    public function getLogoAttribute($value)
    {

        if ($value) {


            return \Storage::disk('user')->url($value);
        }
        return $value;

    }


}
