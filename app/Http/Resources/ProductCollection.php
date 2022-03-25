<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Database\Eloquent\Collection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect($this->collection)->map(function($product) {
            $data =  [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'permalink' => $product->permalink,
                'product_id' => $product->product_id,
                'stock_status' => $product->stock_status,
                'price' => $product->price,
                'regular_price' => $product->regular_price,
                'sale_price' => $product->sale_price,
                'price_html' => $product->settings->price_html,
                'brands' => $this->getCollectionMapped($product->brands),
                'categories' => $this->getCollectionMapped($product->categories),
                'sku' => $product->sku,
                'status' => $product->status,
                'type' => $product->type,
                'image' => null
            ];

            if ($product->images->isNotEmpty()) {
                $data['image'] = $product->images->first()->getImageUrl();
            }

            return $data;
        });
    }

    /**
     * Get collection like brands, tags or categories.
     *
     * @param Collection $collection
     * @return array
     */
    protected function getCollectionMapped(Collection $collection): array {
        if ($collection->isEmpty()) {
            return [];
        }

        return $collection->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug
            ];
        })->toArray();
    }
}
