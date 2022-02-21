<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\ProductImage as WooProductImage;

class ProductImage extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void {
        $this->integer('id');
        $this->string('date_created', null);
        $this->string('date_modified', null);
        $this->string('src');
        $this->string('name');
        $this->string('alt');
        $this->integer('product_id');
    }

    /**
     * Sync Customer
     *
     * @return WooProductImage
     */
    public function sync(): WooProductImage {
        $image = WooProductImage::firstOrNew(['product_image_id' => $this->id]);
        $image->fill($this->toArray('product_image_id'));
        $image->save();

        return $image;
    }
}
