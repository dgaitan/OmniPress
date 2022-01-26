<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $customer = $this->customer ? [
            'customer_id' => $this->customer->id,
            'name' => $this->customer->getFullName(),
            'email' => $this->customer->email
        ] : null;
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'total' => $this->total,
            'shipping' => $this->shipping,
            'date_completed' => $this->date_completed ? $this->date_completed->format('F j, Y at H:i:s') : null,
            'customer' => $customer
        ];
    }
}
