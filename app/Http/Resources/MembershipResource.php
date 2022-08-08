<?php

namespace App\Http\Resources;

use App\Models\WooCommerce\Product;
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
            'price' => $this->price,
            'shipping_status' => $this->shipping_status,
            'status' => $this->status,
            'last_payment_intent' => $this->last_payment_intent,
            'giftProduct' => null,
        ];

        if ($this->customer) {
            $data['customer'] = new CustomerResource($this->customer);
        }

        if ($this->kindCash) {
            $data['cash'] = new KindCashResource($this->kindCash);
        }

        $data['orders'] = new OrderCollection(
            $this->orders()->orderBy('id', 'desc')->get()
        );

        if ($this->gift_product_id) {
            $giftProduct = Product::with('images')
                ->whereProductId($this->gift_product_id)->first();

            if (! is_null($giftProduct)) {
                $data['giftProduct'] = [
                    'id' => $giftProduct->id,
                    'product_id' => $giftProduct->product_id,
                    'name' => $giftProduct->name,
                    'sku' => $giftProduct->sku,
                    'image' => $giftProduct->featuredImage(),
                ];
            }
        }

        return $data;
    }
}
