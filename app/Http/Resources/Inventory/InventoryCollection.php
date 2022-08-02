<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->collection)->map(function ($product) {
            return [
                'id' => $product->id,
                'product_id' => $product->product_id,
                'parent_id' => $product->parent_id,
                'name' => $product->name,
                'type' => $product->type,
                'sku' => $product->sku,
                'price' => $product->price,
                'regular_price' => $product->regular_price,
                'sale_price' => $product->sale_price,
                'status' => $product->status,
                'stock' => $product->stock_quantity
            ];
        });
    }
}
