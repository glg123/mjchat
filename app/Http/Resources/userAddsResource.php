<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userAddsResource extends JsonResource
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
            'id'                =>            $this->id,
            'Url'                =>           $this->url,
            'distance'                =>           $this->distance,
            'img'               =>            asset('uploads/adds/'.$this->img),
        ];
    }
}
