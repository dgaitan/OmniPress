<?php

namespace App\Http\Resources\Subscription;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'status' => $this->status,
            'start_date' => $this->start_date->toDateString(),
            'next_payment_date' => $this->next_payment_date->toDateString(),
            'last_payment' => $this->last_payment ? $this->last_payment->toDateString() : null,
            'total' => $this->total,
            'payment_interval' => $this->payment_interval,
            'cause' => $this->cause,
            'customer' => null,
            'items' => null,
            'logs' => null
        ];

        if (! is_null($this->customer)) {
            $data['customer'] = [
                'id' => $this->customer->id,
                'username' => $this->customer->username,
                'first_name' => $this->customer->first_name,
                'last_name' => $this->customer->last_name,
                'email' => $this->customer->email,
                'avatar_url' => $this->customer->avatar_url
            ];
        }

        if ($this->items->isNotEmpty()) {
            $data['items'] = $this->items->map(function ($i) {
                $product = $i->product;

                return [
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'image' => $product->images->first()?->getImageUrl(),
                        'sku' => $product->sku
                    ],
                    'regular_price' => $i->regular_price,
                    'price' => $i->price,
                    'total' => $i->total,
                    'quantity' => $i->quantity
                ];
            });
        }

        if ($this->logs->isNotEmpty()) {
            $data['logs'] = $this->logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'by' => $log->by,
                    'message' => $log->message,
                    'created_at' => $log->created_at->format('F j, Y H:i:s a')
                ];
            });
        }

        return $data;
    }
}
