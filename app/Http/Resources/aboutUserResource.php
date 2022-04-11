<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class aboutUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
          $folowstate=DB::table('follow_users')->where('follow_id','=',$this->id)->where('follower_id','=',$this->check_id)->where('status',1)->count();
          $folowers=DB::table('follow_users')->where('follow_id','=',$this->id)->where('status',1)->count();
          $folowing=DB::table('follow_users')->where('follower_id','=',$this->id)->where('status',1)->count();
          $posts=DB::table('posts')->where('user_id','=',$this->id)->where('type',2)->count();
          $locations=DB::table('user_locations')->where('user_id','=',$this->id)->count();
           return [
            'id'                       =>        $this->id,
            'first_name'               =>        $this->first_name ?? "",
            'last_name'                =>        $this->last_name ?? "",
            'name'                     =>        $this->first_name . " " . $this->last_name?? "",
            'user_name'                =>        $this->user_name ?? "",
            'img'                      =>        asset('uploads/users/'.$this->img),
            'userType'                 =>        1,
            'verified'                 =>        0,
            'follow_status'            =>        $folowstate ?? 0,
            'followers'                =>        $folowers ?? 0,
            'following'                =>        $folowing ?? 0,
            'posts'                    =>        $posts ?? 0,
            'locations'                =>        $locations ?? 0,
            'facebook_link'            =>        $this->facebook_link ?? "",
            'twitter_link'             =>        $this->twitter_link ?? "",
            'instagram_link'           =>        $this->instagram_link ?? "",
            'bio'                      =>        $this->bio ?? "",
        ];
    }
}
