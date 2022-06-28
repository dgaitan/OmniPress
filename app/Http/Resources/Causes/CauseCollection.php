<?php

namespace App\Http\Resources\Causes;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CauseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->collection)->map(function ($cause) {
            return [
                'id' => $cause->id,
                'name' => $cause->name,
                'cause_type' => $cause->cause_type,
                'cause_type_label' => $cause->getCauseType(),
                'image_url' => $cause->getImage(),
                'beneficiary' => $cause->beneficiary,
            ];
        });
    }
}
