<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user=User::find($this->user_id);
        $likecount=DB::table('strorylikes')->where('story_id','=',$this->id)->where('status','=',1)->count();
        $commentcount=DB::table('comments')->where('post_id','=',$this->id)->where('status','=',1)->count();
        $likestatusx=DB::table('strorylikes')->where('story_id','=',$this->id)->where('user_id',$this->check_id)->value('status');
        $followstatusx=DB::table('follow_users')->where('follow_id','=',$this->user_id)->where('follower_id',$this->check_id)->value('status');
        $viewcount=DB::table('story_views')->where('story_id',$this->id)->count();
        if ($likestatusx==1){
            $likestatus=1;
        }else{
            $likestatus=0;
        }
        if ($followstatusx==1){
            $followstatus=1;
        }else{
            $followstatus=0;
        }
        return [
            'id'                   =>            $this->id,
            'lat'                  =>            $this->lat,
            'long'                 =>            $this->long,
            'text'                 =>            $this->comment,
            'img'                  =>           $this->media,
            'user_image'           =>           $user->img,
            'user_name'            =>            $user->first_name,
            'user_id'              =>            $user->id,
            'likeCount'            =>            $likecount,
            'viewsCount'           =>           $viewcount,
            'commentCount'         =>            $commentcount ?? 0,
            'like_status'          =>            $likestatus,
            'follow_status'        =>            $followstatus,
            'created_at'           =>            strtotime($this->created_at),
        ];
    }
}
