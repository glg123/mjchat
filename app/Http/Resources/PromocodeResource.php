<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromocodeResource extends JsonResource
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
            'id'                =>                     $this->id,
            'code'              =>                     $this->code,
            'promoValue'        =>                     $this->promoValue,
            'maxValue'          =>                     $this->maxValue,
            'valid_to'          =>                     strtotime($this->valid_to),
        ];
    }
}
