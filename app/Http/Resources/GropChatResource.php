<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GropChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $members = ChatUserInfo::collection($this->members);
        $onwer = ChatUserInfo::collection([$this->onwer]);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'members' => $members,
            'onwer' => $onwer,
            'created_at' => strtotime($this->created_at),
        ];
    }
}
