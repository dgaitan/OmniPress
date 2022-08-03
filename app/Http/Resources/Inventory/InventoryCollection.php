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
        $data = [];

        foreach ($this->collection as $product) {
            $data[] = $product;

            if ($product->variations->isNotEmpty()) {
                foreach ($product->variations as $v) {
                    $data[] = $v;
                }
            }
        }

        return collect($data)->map(function ($product) {
            return [
                'product_id' => $product->product_id,
                'parent_id' => $product->type === 'variation'
                    ? $product->parent->product_id : null,
                'name' => $product->name,
                'type' => $product->type,
                'sku' => $product->sku,
                'stock' => $product->stock_quantity
            ];
        });
    }
}
