<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $items = collect($this->collection)->map(function($item){
            $line = [
                'id' => $item->id,
                'order_line_id' => $item->order_line_id,
                'name' => $item->name,
                'product_id' => $item->product_id,
                'variation_id' => $item->variation_id,
                'quantity' => $item->quantity,
                'tax_class' => $item->tax_class,
                'subtotal' => $item->subtotal,
                'subtotal_tax' => $item->subtotal_tax,
                'total' => $item->total,
                'taxes' => $item->taxes,
                'meta_data' => $item->taxes,
                'sku' => $item->sku,
                'price' => $item->price,
                'product' => null
            ];

            if (! is_null($item->product)) {
                $line['product'] = new ProductResource($item->product);
            }

            return $line;
        });
        
        return $items;
    }
}
