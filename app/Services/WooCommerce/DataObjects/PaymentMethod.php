<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\PaymentMethod as WooPaymentMethod;

class PaymentMethod extends BaseObject implements DataObjectContract
{
    /**
     * Order Schame
     *
     * @return void
     */
    protected function schema(): void {
        $this->string('id');
        $this->string('title');
        $this->string('description', '');
        $this->integer('order', 0);
        $this->boolean('enabled', true);
        $this->string('method_title');
        $this->array('method_supports', []);
        $this->array('settings', []);
    }

    /**
     * Sync Customer
     *
     * @return WooPaymentMethod
     */
    public function sync(): WooPaymentMethod {
        $pm = WooPaymentMethod::firstOrNew(['payment_method_id' => $this->id]);
        $data = $this->toArray('payment_method_id');

        $pm->fill($data);
        $pm->save();

        return $pm;
    }
}
