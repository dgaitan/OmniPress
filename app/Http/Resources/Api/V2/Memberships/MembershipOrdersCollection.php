<?php

namespace App\Http\Resources\Api\V2\Memberships;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MembershipOrdersCollection extends ResourceCollection {
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return collect($this->collection)->map(function ($order) {
            return [
                'id' => $order->id,
                'woo_order_id' => $order->order_id,
                'order_key' => $order->order_key,
                'status' => $order->status,
                'date_created' => $order->date_created,
                'total' => $order->total,
            ];
        });
    }
}
