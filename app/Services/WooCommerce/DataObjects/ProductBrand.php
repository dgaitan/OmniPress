<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\Brand;

class ProductBrand extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void {
        $this->integer('id');
        $this->string('name');
        $this->string('slug');
    }

    /**
     * Sync Brand
     *
     * @return Brand
     */
    public function sync(): Brand {
        $brand = Brand::firstOrNew(['woo_brand_id' => $this->id]);
        $brand->fill($this->toArray('woo_brand_id'));
        $brand->save();

        return $brand;
    }
}
