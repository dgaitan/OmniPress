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
                'product_id' => $product->product_id,
                'parent_id' => $product->parent_id,
                'name' => $product->name,
                'type' => $product->type,
                'status' => $product->status,
                'sku' => $product->sku,
                'price' => $product->getMoneyValue('price')->formatByDecimal(),
                'regular_price' => $product->getMoneyValue('regular_price')->formatByDecimal(),
                'sale_price' => $product->getMoneyValue('sale_price')->formatByDecimal(),
                'stock' => $product->stock_quantity
            ];
        });
    }
}
