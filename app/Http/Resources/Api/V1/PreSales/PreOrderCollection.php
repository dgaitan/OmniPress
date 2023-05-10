<?php

namespace App\Http\Resources\Api\V1\PreSales;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PreOrderCollection extends ResourceCollection {
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return collect($this->collection)->map(function ($order) {
            return new PreOrderResource($order);
        });
    }
}
