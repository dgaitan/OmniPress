<?php

namespace App\Http\Resources\Causes;

use Illuminate\Http\Resources\Json\JsonResource;

class CauseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cause_type' => $this->cause_type,
            'cause_type_label' => $this->getCauseType(),
            'image_url' => $this->getImage(),
            'beneficiary' => $this->beneficiary,
        ];
    }
}
