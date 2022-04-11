<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->first_name){
                return [
                    'id'                       =>        $this->id,
                    'name'                     =>        $this->first_name . " " . $this->last_name?? "",
                    'user_name'                =>        $this->user_name ?? "",
                    'img'                      =>        asset('uploads/users/'.$this->img),
                ];
        }else{
            return [
                'id'                       =>        $this->id,
                'name'                     =>        $this->address ?? "",
                'lat'                      =>        $this->lat,
                'long'                     =>        $this->long,
            ];
        }

    }
}
