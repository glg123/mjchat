<?php

namespace App\Http\Resources;

use App\Models\appuser;
use App\Models\Comment;
use App\Models\CommentLikes;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CommentResource extends JsonResource
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
        if ($user->comment_privacy==1){
                $show=1;
        }else if ($user->comment_privacy==2){
                   $follwoing=DB::table('follow_users')->where('follower_id','=',$user->id)->where('follow_id','=',$this->cur_id)->where('status','=',1)->count();
                   $follwoed=DB::table('follow_users')->where('follow_id','=',$this->cur_id)->where('follower_id','=',$user->id)->where('status','=',1)->count();
                   if ($follwoing==0 || $follwoed==0){
                       $show=0;
                   }else{

                       $show=1;
                   }
        }else if ($user->comment_privacy==3){
            $follwoing=DB::table('follow_users')->where('follower_id','=',$user->id)->where('follow_id','=',$this->cur_id)->where('status','=',1)->count();
            if ($follwoing==0){
                $show=0;
            }else{
                $show=1;
            }
        }else{
            $follwoed=DB::table('follow_users')->where('follow_id','=',$user->id)->where('follower_id','=',$this->cur_id)->where('status','=',1)->count();
            if ($follwoed==0){
                $show=0;
            }else{
                $show=1;
            }
        }

        if ($user->id==$this->cur_id){
            $show=1;
        }

        $likestate=DB::table('comment_likes')->where('comment_id',$this->id)->where('user_id',$this->cur_id)->value('status');
        $likes=DB::table('comment_likes')->where('comment_id',$this->id)->count();
        if ($likestate==1){
            $like_state=1;
        }else{
            $like_state=0;
        }
        $check=Comment::where('perant_id',$this->id)->count();
        if ($check>0){
            $replies=Comment::where('perant_id',$this->id)->get();
            foreach ($replies as $key=>$ab){
                $replies[$key]->cur_id=$this->cur_id;
            }
            $repres=CommentResource::collection($replies);
        }else{
            $repres=[];
        }
        return [
            'id'                       =>        $this->id,
            'text'                     =>        $this->text,
            'name'                     =>        $user->first_name . " " . $user->last_name?? "",
            'user_name'                =>        $user->user_name ?? "",
            'like_state'               =>        $like_state,
            'likes'                    =>        $likes ?? 0,
            'show_comment'             =>        $show ?? 0,
            'img'                      =>        asset('uploads/users/'.$user->img),
            'created_at'               =>        strtotime($this->created_at),
            'replies'                  =>        $repres,
        ];
    }
}
