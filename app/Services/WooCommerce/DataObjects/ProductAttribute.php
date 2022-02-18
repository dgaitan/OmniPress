<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\ProductAttribute as WooProductAttribute;

class ProductAttribute extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void {
        $this->integer('id');
        $this->string('name');
        $this->integer('position');
        $this->boolean('visible');
        $this->boolean('variation');
        $this->array('options');
    }

    /**
     * Sync Customer
     *
     * @return WooProductAttribute
     */
    public function sync(): WooProductAttribute {
        $attribute = WooProductAttribute::firstOrNew(['attribute_id' => $this->id]);
        $attribute->fill($this->toArray('attribute_id'));
        $attribute->save();

        return $attribute;
    }
}
