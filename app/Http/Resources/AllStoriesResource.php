<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AllStoriesResource extends JsonResource
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
        return [
                    'id'                  =>            $this->id,
                    'lat'                 =>            $this->lat,
                    'long'                =>            $this->long,
                    'img'                 =>            asset('uploads/posts/'.$this->media),
                    'user_name'           =>            $user->first_name,
                 //   'distance'            =>            $this->distance,
                    'created_at'          =>            strtotime($this->created_at),
        ];
    }
}
