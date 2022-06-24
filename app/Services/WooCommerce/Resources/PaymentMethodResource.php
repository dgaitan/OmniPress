<?php

namespace App\Services\WooCommerce\Resources;

use App\Services\Contracts\ResourceContract;
use App\Services\Resources\BaseResource;
use App\Services\WooCommerce\Factories\PaymentMethodFactory;

class PaymentMethodResource extends BaseResource implements ResourceContract
{
    /**
     * Endpoint Name
     *
     * @var string
     */
    public string $endpoint = 'payment_gateways';

    /**
     * Product Factory
     *
     * @var string
     */
    public string $factory = PaymentMethodFactory::class;

    /**
     * Get all and sync.
     *
     * Basically Sync all elements
     *
     * @param  int  $per_page
     * @return void
     */
    public function syncAll(int|null $perPage, int $page = 1, int $sync_id = 0): void
    {
        $sync = \App\Models\Sync::find($sync_id);

        if ($sync->status === \App\Models\Sync::COMPLETED) {
            return;
        }

        try {
            $response = $this->all();

            if ($response) {
                $response->map(fn ($item) => $item->sync());
                $sync->current_page = $page + 1;
                $sync->save();
            }

            $sync->complete();
        } catch (\Exception $e) {
            $sync->status = \App\Models\Sync::FAILED;
            $sync->save();
            $sync->add_log(sprintf('Error: %s', $e->getMessage()));
        }
    }
}
