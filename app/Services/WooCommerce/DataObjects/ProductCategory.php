<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Models\WooCommerce\Category;
use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;

class ProductCategory extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void
    {
        $this->integer('id');
        $this->string('name');
        $this->string('slug');
    }

    /**
     * Sync Customer
     *
     * @return Category
     */
    public function sync(): Category
    {
        $category = Category::firstOrNew(['woo_category_id' => $this->id]);
        $category->fill($this->toArray('woo_category_id'));
        $category->save();

        return $category;
    }
}
