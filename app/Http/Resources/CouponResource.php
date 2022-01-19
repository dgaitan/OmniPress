<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'coupon_id' => $this->coupon_id,
            'created_at' => $this->date_created->format('F j, Y'),
            'discount_type' => $this->discount_type,
            'amount' => $this->amount,
            'code' => $this->code,
            'description' => $this->description,
            'usage_count' => $this->usage_count
        ];
    }
}
