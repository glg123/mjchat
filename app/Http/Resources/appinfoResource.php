<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class appinfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (app()->getLocale()=='ar'){
            $about=$this->about_ar;
            $policy=$this->policy_ar;
        }else{
            $about=$this->about_en;
            $policy=$this->policy_en;
        }
        return [
            'about'             =>         $about,
            'policy'             =>         $policy,
        ];
    }
}
