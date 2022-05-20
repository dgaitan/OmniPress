<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\SearchEngine\Index as EngineIndex;

class Index extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void {
        $this->integer('id');
        $this->string('content_type');
        $this->string('title');
        $this->string('slug');
        $this->string('content');
        $this->array('relations');
    }

    /**
     * Sync Customer
     *
     * @return EngineIndex
     */
    public function sync(): EngineIndex {
        $index = EngineIndex::firstOrNew(['content_id' => $this->id]);
        $index->fill($this->toArray('content_id'));
        $index->save();

        return $index;
    }
}
