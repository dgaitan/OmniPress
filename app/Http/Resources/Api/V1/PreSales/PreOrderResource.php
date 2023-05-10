<?php

namespace App\Http\Resources\Api\V1\PreSales;

use Illuminate\Http\Resources\Json\JsonResource;

class PreOrderResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'woo_order_id' => $this->woo_order_id,
            'customer_email' => $this->customer_email,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'release_date' => $this->release_date,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'sub_total' => $this->sub_total,
            'taxes' => $this->taxes,
            'shipping' => $this->shipping,
            'total' => $this->total,
            'released' => $this->released
        ];
    }
}
