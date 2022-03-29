<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product = [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'permalink' => $this->permalink,
            'store_permalink' => $this->getStorePermalink(),
            'kinja_permalink' => $this->getKinjaPermalink(),
            'sku' => $this->sku,
            'date_created' => $this->date_created ? $this->date_created->format('F j, Y') : '',
            'date_modified' => $this->date_created ? $this->date_created->format('F j, Y') : '',
            'type' => $this->type,
            'status' => $this->status,
            'featured' => $this->featured,
            'on_sale' => $this->on_sale,
            'purchasable' => $this->purchasable,
            'virtual' => $this->virtual,
            'manage_stock' => $this->manage_stock,
            'stock_quantity' => $this->stock_quantity,
            'stock_status' => $this->stock_status,
            'sold_individually' => $this->sold_individually,
            'price' => $this->price,
            'regular_price' => $this->regular_price,
            'sale_price' => $this->sale_price,
            'settings' => $this->settings,
            'meta_data' => $this->meta_data,
            'images' => [],
            'categories' => $this->getCollectionMapped($this->categories),
            'tags' => $this->getCollectionMapped($this->tags),
            'attributes' => [],
            'variations' => [],
            'brands' => $this->getCollectionMapped($this->brands),
            'parent' => null
        ];

        // if ($this->variations()->count() > 0) {
        //     $products['variations'] = new self($this);
        // }

        if (! is_null($this->parent)) {
            $product['parent'] = new self($this->parent);
        }

        if ($this->images->isNotEmpty()) {
            $product['images'] = $this->images->map(function($image){
                return [
                    'src' => $image->getImageUrl(),
                    'name' => $image->name,
                    'alt' => $image->alt
                ];
            });
        }

        if ($this->productAttributes->isNotEmpty()) {
            $product['attributes'] = $this->attributes;
        }

        return $product;
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
