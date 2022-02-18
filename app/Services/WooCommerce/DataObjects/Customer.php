<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\Customer as WooCustomer;

class Customer extends BaseObject implements DataObjectContract
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
        $this->string('email');
        $this->string('first_name');
        $this->string('last_name');
        $this->string('username');
        $this->array('shipping');
        $this->array('billing');
        $this->string('role');
        $this->boolean('is_paying_customer', false);
        $this->string('avatar_url');
        $this->array('meta_data', []);
    }

    /**
     * Sync Customer
     *
     * @return WooCustomer
     */
    public function sync(): WooCustomer {
        $customer = WooCustomer::firstOrNew(['customer_id' => $this->id]);
        $data = $this->toArray();

        if ($data['meta_data']) {
            $data['meta_data'] = collect($data['meta_data'])->map(function($meta) {
                return (array) $meta;
            });
        }

        $customer->fill($data);
        $customer->save();

        return $customer;
    }
}
