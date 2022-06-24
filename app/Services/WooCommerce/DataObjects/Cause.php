<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Models\Causes\Cause as ModelCause;
use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;

class Cause extends BaseObject implements DataObjectContract
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
        $this->string('cause_type', null);
        $this->string('beneficiary');
        $this->string('image_url');
    }

    /**
     * Sync Customer
     *
     * @return ModelCause
     */
    public function sync(): ModelCause
    {
        $cause = ModelCause::firstOrNew(['cause_id' => $this->id]);
        $cause->fill($this->toArray('cause_id'));
        $cause->save();

        return $cause;
    }
}
