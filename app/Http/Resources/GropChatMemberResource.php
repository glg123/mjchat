<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GropChatMemberResource extends JsonResource
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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'members' => $members,

        ];
    }
}
