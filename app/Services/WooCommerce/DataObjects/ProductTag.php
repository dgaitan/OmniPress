<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\Tag;

class ProductTag extends BaseObject implements DataObjectContract
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
     * Sync Customer
     *
     * @return Tag
     */
    public function sync(): Tag {
        $tag = Tag::firstOrNew(['woo_tag_id' => $this->id]);
        $tag->fill($this->toArray('woo_tag_id'));
        $tag->save();

        return $tag;
    }
}
