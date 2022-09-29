<?php

namespace App\Http\Resources\Printforia;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PrintforiaOrdersCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orders = collect($this->collection)->map(function ($order) {
            $data = [
                'id' => $order->id,
                'order' => [
                    'id' => $order->order->id,
                    'order_id' => $order->order->order_id,
                ],
                'created_at' => $order->created_at,
                'status' => $order->status,
                'printforia_order_id' => $order->printforia_order_id,
                'shipping_method' => $order->shipping_method,
                'customer_reference' => $order->customer_reference,
                'ship_to_address' => $order->ship_to_address,
                'return_to_address' => $order->return_to_address,
                'ship_to_address_formatted' => $order->shippingAddress(),
                'return_to_address_formatted' => $order->returnAddress(),
                'permalink' => $order->getPermalink(),
                'woo_permalink' => $order->getWooOrderPermalink(),
            ];

            return $data;
        });

        return $orders;
    }
}
