<?php

namespace App\Http\Resources;

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
            'images' => null,
            'categories' => null,
            'tags' => null,
            'attributes' => null,
            'variations' => null,
            'parent' => null
        ];

        // if ($this->variations()->count() > 0) {
        //     $products['variations'] = new self($this);
        // }

        if (! is_null($this->parent)) {
            $product['parent'] = new self($this->parent);
        }

        if ($this->images()->exists()) {
            $product['images'] = collect($this->images)->map(function($image){
                return [
                    'src' => $image->src,
                    'name' => $image->name,
                    'alt' => $image->alt
                ];
            });
        }

        if ($this->categories()->exists()) {
            $product['categories'] = $this->categories()->get();
        }

        if ($this->tags()->exists()) {
            $product['tags'] = $this->tags()->get();
        }

        if ($this->productAttributes()->exists()) {
            $product['attributes'] = $this->attributes;
        }

        return $product;
    }
}
