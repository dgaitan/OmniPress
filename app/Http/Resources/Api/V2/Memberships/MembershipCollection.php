<?php

namespace App\Http\Resources\Api\V2\Memberships;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MembershipCollection extends ResourceCollection {
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return collect($this->collection)->map(function ($membership) {
            $giftProduct = [];
            if ($membership->gift_product_id && !$membership->isAwaitingPickGift()) {
                $giftProduct = $membership->getCurrentGiftProduct();

                if ($giftProduct) {
                    $giftProduct = [
                        'id' => $giftProduct->id,
                        'woo_product_id' => $giftProduct->product_id,
                        'name' => $giftProduct->name,
                        'sku' => $giftProduct->sku,
                    ];
                }
            }

            $currentOrder = [];
            if ($membership->getCurrentOrder()) {
                $currentOrder = $membership->getCurrentOrder();
                $currentOrder = [
                    'id' => $currentOrder->id,
                    'woo_order_id' => $currentOrder->order_id,
                ];
            }

            return [
                'id' => $membership->id,
                'customer_email' => $membership->customer_email,
                'customer_id' => $membership->customer->customer_id,
                'start_at' => $membership->start_at,
                'end_at' => $membership->end_at,
                'price' => $membership->price,
                'price_as_money' => $membership->getPriceAsMoney(),
                'shipping_status' => $membership->shipping_status,
                'status' => $membership->status,
                'user_picked_gift' => $membership->user_picked_gift,
                'gift_product' => $giftProduct,
                'order' => $currentOrder,
                // Flags
                'is_active' => $membership->isActive(),
                'is_in_renewal' => $membership->isInRenewal(),
                'is_awaiting_pick_gift' => $membership->isAwaitingPickGift(),
                'is_cancelled' => $membership->isCancelled(),
                'is_expired' => $membership->isExpired(),
                'cash' => $membership->kindCash->toArray()
            ];
        });
    }
}
