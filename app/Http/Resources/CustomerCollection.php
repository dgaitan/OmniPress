<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->collection)->map(function ($customer) {
            return [
                'id' => $customer->id,
                'customer_id' => $customer->customer_id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'username' => $customer->username,
                'full_name' => $customer->getfullName(),
                'role' => $customer->role,
                'avatar' => $customer->avatar_url,
                'storePermalink' => $customer->getPermalinkOnStore(),
                'date_created' => $customer->getDateCreated(),
            ];
        });
    }
}
