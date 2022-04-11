<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SingleStoryResource extends JsonResource
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
        $viewcount=DB::table('story_views')->where('story_id',$this->id)->count();
        $comments=DB::table('comments')->where('post_id','=',$this->id)->where('status','=',1)->count();
        $like_status=DB::table('strorylikes')->where('story_id','=',$this->id)->where('user_id','=',$this->current_users)->value('status');
        if ($like_status==1){
            $like_statusx=1;
        }else{
            $like_statusx=0;
        }
        return [
            'id'                  =>            $this->id,
            'lat'                 =>            $this->lat,
            'long'                =>            $this->long,
            'img'                 =>            asset('uploads/posts/'.$this->media),
            'user_name'           =>            $user->first_name,
            'user_id'             =>            $user->id,
            'user_following'      =>            false,
            'views'               =>            $viewcount,
            'privacy'             =>            $this->status,
            'likes'               =>            $likecount,
            'like_status'         =>            $like_statusx,
            'comment_count'         =>            $comments ?? 0,
            'text'                =>            $this->comment ?? '',
            'created_at'          =>            strtotime($this->created_at),
        ];
    }
}
