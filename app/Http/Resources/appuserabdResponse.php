<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class appuserabdResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
                    'id'                     =>        $this->id,
                    'first_name'             =>        $this->first_name ?? "",
                    'last_name'              =>        $this->last_name ?? "",
                    'user_name'              =>        $this->user_name     ?? "",
                    'img'                    =>        asset('uploads/users/'.$this->img),
                    'gender'                 =>        $this->gender        ??  "",
                    'userType'               =>        1,
                    'date_of_birth'          =>        $this->date_of_birth ?? "",
                    'comment_privacy'          =>        $this->comment_privacy ?? 1,
                    'email'                  =>        $this->email         ?? "",
                //    'api_token'              =>        $this->api_token     ?? "",
        ];
    }
}
