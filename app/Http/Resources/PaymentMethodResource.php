<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
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
            'payment_method_id' => $this->payment_method_id,
            'title' => $this->title,
            'description' => $this->description,
            'method_title' => $this->method_title,
            'method_description' => $this->method_description,
        ];
    }
}
