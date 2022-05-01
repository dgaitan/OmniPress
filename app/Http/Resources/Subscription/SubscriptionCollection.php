<?php

namespace App\Http\Resources\Subscription;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->collection)->map(function ($item) {
            $data = [
                'id' => $item->id,
                'status' => $item->status,
                'start_date' => $item->start_date->toDateString(),
                'next_payment_date' => $item->next_payment_date->toDateString(),
                'last_payment' => $item->last_payment ? $item->last_payment->toDateString() : null,
                'total' => $item->total,
                'payment_interval' => $item->payment_interval,
                'cause' => $item->cause,
                'customer' => null,
                'items' => null
            ];

            if (! is_null($item->customer)) {
                $data['customer'] = [
                    'id' => $item->customer->id,
                    'username' => $item->customer->username,
                    'email' => $item->customer->email
                ];
            }

            if ($item->items->isNotEmpty()) {
                $data['items'] = $item->items->map(function ($i) {
                    $product = $i->product;

                    return [
                        'product' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'image' => $product->images->first()?->getImageUrl()
                        ],
                        'regular_price' => $i->regular_price,
                        'price' => $i->price,
                        'total' => $i->total,
                        'quantity' => $i->quantity
                    ];
                });
            }

            return $data;
        });
    }
}
