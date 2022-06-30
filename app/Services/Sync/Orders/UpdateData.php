<?php

namespace App\Services\Sync\Orders;

use App\Jobs\SingleWooCommerceSync;
use App\Models\Sync;
use App\Models\WooCommerce\Order;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

class UpdateData extends BaseService
{
    /**
     * Undocumented function
     *
     * @param [type] $content_type
     * @param [type] $element_id
     */
    public function __construct(public Order $order, public array $params)
    {}

    /**
     * Handle the Service
     *
     * @return void
     */
    public function handle()
    {
        $api = \App\Services\WooCommerce\WooCommerceService::make();
        $api->orders()->update($this->order->order_id, $this->params, true);
    }
}
