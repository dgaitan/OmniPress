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
        $data = $this->toArray('customer_id');

        if ($data['meta_data']) {
            $meta_data = [];

            foreach ($data['meta_data'] as $meta) {
                $meta = (array) $meta;

                if (! isset($meta['key'])) continue;
                if ($meta['key'] === '_stripe_customer') continue;

                $meta_data[] = $meta;
            }

            $data['meta_data'] = $meta_data;
        }

        $customer->fill($data);
        $customer->save();

        return $customer;
    }
}
