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
        $order = [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'number' => $this->number,
            'order_key' => $this->order_key,
            'currency' => $this->currency,
            'date' => $this->getDateCompleted(),
            'sub_total' => $this->getSubtotal(),
            'total' => $this->total,
            'shipping_total' => $this->shipping_total,
            'total_tax' => $this->total_tax,
            'discount_total' => $this->discount_total,
            'discount_tax' => $this->discount_tax,
            'giftcard_total' => $this->giftcard_total,
            'billing_address' => $this->billingAddress(),
            'billing' => $this->billing,
            'shipping_address' => $this->shippingAddress(),
            'shipping' => $this->billing,
            'meta_data' => $this->meta_data,
            'tax_lines' => $this->tax_lines,
            'fee_lines' => $this->fee_lines,
            'coupon_lines' => $this->coupon_lines,
            'shipping_lines' => $this->shipping_lines,
            'giftcards' => $this->giftcards,
            'permalink_on_store' => $this->getPermalinkOnStore(),
            // Default Values for relationships
            'customer' => null,
            'items' => [],
            'membership' => null,
            'payment_method' => null,
        ];

        if (! is_null($this->customer)) {
            $order['customer'] = [
                'avatar_url' => $this->customer->avatar_url,
                'name' => $this->customer->getFullName(),
                'email' => $this->customer->email,
                'id' => $this->customer->id,
                'customer_id' => $this->customer->customer_id,
            ];
        }

        if ($this->items()->exists()) {
            $order['items'] = new OrderItemCollection($this->items);
        }

        if ($this->payment_id) {
            $order['payment_method'] = new PaymentMethodResource(
                $this->paymentMethod
            );
        }

        return $order;
    }
}
