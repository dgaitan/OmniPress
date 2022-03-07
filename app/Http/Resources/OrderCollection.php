<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orders = collect($this->collection)->map(function($order) {
            $data = [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'status' => $order->status,
                'total' => $order->total,
                'shipping' => $order->shipping,
                'date' => $order->getDateCompleted(),
            ];

            if ($order->customer) {
                $customer = $order->customer ? [
                    'customer_id' => $order->customer->id,
                    'name' => $order->customer->getFullName(),
                    'email' => $order->customer->email
                ] : null;

                $data['customer'] = $customer;
            }

            return $data;
        });

        return $orders;
    }
}
