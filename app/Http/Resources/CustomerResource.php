<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'date_created' => $this->date_created->toDateString(),
            'date_modified' => $this->date_created->toDateString(),
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role' => $this->role,
            'username' => $this->username,
            'billing' => $this->billing,
            'shipping' => $this->shipping,
            'is_paying_customer' => $this->is_paying_customer,
            'avatar_url' => $this->avatar_url,
            'meta_data' => $this->meta_data ?? []
        ];
    }
}
