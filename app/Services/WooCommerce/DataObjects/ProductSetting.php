<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;

class ProductSetting extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void
    {
        $this->string('date_on_sale_from', null);
        $this->string('date_on_sale_to', null);
        $this->string('price_html');
        $this->string('short_description');
        $this->string('catalog_visibility');
        $this->string('description');
        $this->integer('total_sales');
        $this->integer('download_limit');
        $this->integer('download_expiry');
        $this->string('external_url');
        $this->string('button_text');
        $this->string('tax_status');
        $this->string('tax_class');
        $this->string('backorders');
        $this->boolean('backordered');
        $this->float('weight');
        $this->array('dimensions');
        $this->boolean('shipping_required');
        $this->boolean('shipping_taxable');
        $this->integer('shipping_class_id');
        $this->boolean('reviews_allowed');
        $this->string('average_rating');
        $this->integer('rating_count');
        $this->array('related_ids');
        $this->array('upsell_ids');
        $this->array('cross_sell_ids');
        $this->string('purchase_note');
        $this->array('default_attributes');
        $this->array('variations');
        $this->array('grouped_products');
        $this->integer('menu_order');
        $this->array('downloads');
    }
}
