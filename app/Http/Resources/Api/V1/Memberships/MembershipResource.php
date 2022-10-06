<?php

namespace App\Http\Resources\Api\V1\Memberships;

use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends JsonResource
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
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'customer_email' => $this->customer_email,
            'product_id' => $this->product->product_id,
            'price' => $this->price,
            'price_as_money' => $this->getPriceAsMoney(),
            'shipping_status' => $this->shipping_status,
            'status' => $this->status,
            'last_payment_intent' => $this->last_payment_intent->toJson(),
            'gift_product' => [],
            'payment_intents' => $this->payment_intents,
            'user_picked_gift' => $this->user_picked_gift,
            'customer' => [
                'id' => $this->customer->id,
                'email' => $this->customer->email,
                'customer_id' => $this->customer->customer_id,
            ],
            'cash' => [
                'points' => $this->kindCash->points,
                'last_earned' => $this->kindCash->last_earned,
            ],
            'is_active' => $this->isActive(),
            'is_in_renewal' => $this->isInRenewal(),
            'is_awaiting_pick_gift' => $this->isAwaitingPickGift(),
            'is_expired' => $this->isExpired(),
            'is_cancelled' => $this->isCancelled(),
        ];

        if ($this->gift_product_id && ! $this->isAwaitingPickGift()) {
            $giftProduct = $this->getCurrentGiftProduct();

            if ($giftProduct) {
                $data['gift_product'] = [
                    'id' => $giftProduct->product_id,
                    'name' => $giftProduct->name,
                    'sku' => $giftProduct->sku,
                ];
            }
        }

        return $data;
    }
}
