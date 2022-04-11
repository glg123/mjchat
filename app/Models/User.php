<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'api_token',
        'confirmation_password_code',
        'confirmation_code',
        'account_type',
        'date_of_birth',
        'user_name',
        'mobile',
        'country_code',
        'points',
        'status',
        'img',
        'comment_privacy',
        'country_id',
        'gender',
        'fb_id',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */


    public function getImgAttribute($value)
    {

        if ($value) {


            return \Storage::disk('users')->url($value);
        }
        return \Storage::disk('users')->url('');

    }

    public function stories(){
        return $this->hasMany(Post::class,'user_id')->where('type','=',1);
    }

    public function posts(){
        return $this->hasMany(Post::class,'user_id')->where('type','=',2);
    }

    public function post_story(){
        return $this->hasMany(Post::class,'user_id');
    }

    public function locations(){
        return $this->hasMany(UserLocation::class,'user_id');
    }

    public function following(){
        return $this->belongsToMany(User::class,'follow_users','follower_id','follow_id');
    }

    public function flowers(){
        return $this->belongsToMany(User::class,'follow_users','follow_id','follower_id');
    }

    public function savedPosts(){
        return $this->belongsToMany(Post::class,'fav_stories','user_id','story_id');
    }
    public function groups(){
        return $this->belongsToMany(GroupChat::class,'group_chat_members','user_id','group_id');
    }

    public function mygroups(){
        return $this->hasMany(GroupChat::class,'user_id',);
    }
    public function blockUsers(){
        return $this->belongsToMany(User::class,'userblocks','user_id','block_user_id')
            ->where('userblocks.status','1');
    }


}
