<?php

namespace App\Http\Resources;

use App\Models\appuser;
use Illuminate\Http\Resources\Json\JsonResource;

class MsgResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user=appuser::find($this->user_id);
        return [
            'id'               =>         $this->id,
            'user_id'               =>         $this->user_id,
            'name'                     =>        $user->first_name . " " . $user->last_name?? "",
            'user_name'                =>        $user->user_name ?? "",
            'img'                      =>        asset('uploads/users/'.$user->img),
            'msg'               =>         $this->msg,

        ];
    }
}
