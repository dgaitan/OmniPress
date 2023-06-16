<?php

namespace App\Http\Resources\Api\V1\Memberships;

use App\Http\Resources\OrderCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        $data = [
            'id' => $this->id,
            'start_at' => $this->start_at,
            'start_at_formatted' => $this->start_at->format('F j, Y'),
            'end_at' => $this->end_at,
            'end_at_formatted' => $this->end_at->format('F j, Y'),
            'customer_email' => $this->customer_email,
            'product_id' => $this->product->product_id,
            'price' => $this->price,
            'price_as_money' => $this->getPriceAsMoney(),
            'shipping_status' => $this->shipping_status,
            'shipping_status_label' => $this->getShippingStatusLabel(),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'last_payment_intent' => $this->last_payment_intent->toJson(),
            'last_payment_intent_formatted' => $this->last_payment_intent->format('F j, Y'),
            'gift_product' => [],
            'payment_intents' => $this->payment_intents,
            'user_picked_gift' => $this->user_picked_gift,
            'customer' => [
                'id' => $this->customer->id,
                'email' => $this->customer->email,
                'customer_id' => $this->customer->customer_id,
                'full_name' => $this->customer->getfullName(),
                'avatar' => $this->customer->avatar_url,
            ],
            'cash' => [
                'points' => $this->kindCash->points,
                'last_earned' => $this->kindCash->last_earned,
                'updated_at' => $this->kindCash->updated_at,
            ],
            'kind_cash' => $this->kindCash->toArray(),
            'is_active' => $this->isActive(),
            'is_in_renewal' => $this->isInRenewal(),
            'is_awaiting_pick_gift' => $this->isAwaitingPickGift(),
            'is_expired' => $this->isExpired(),
            'is_cancelled' => $this->isCancelled(),
        ];

        if ($this->gift_product_id && !$this->isAwaitingPickGift()) {
            $giftProduct = $this->getCurrentGiftProduct();

            if ($giftProduct) {
                $data['gift_product'] = [
                    'id' => $giftProduct->product_id,
                    'name' => $giftProduct->name,
                    'sku' => $giftProduct->sku,
                ];
            }
        }

        $data['orders'] = new OrderCollection(
            $this->orders()->orderBy('id', 'desc')->get()
        );

        return $data;
    }
}
