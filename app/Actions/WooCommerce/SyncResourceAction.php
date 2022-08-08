<?php

namespace App\Actions\WooCommerce;

use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncResourceAction
{
    use AsAction;

    public function handle(
        string $resourceName,
        array $data
    ): Model|null {
        $api = WooCommerceService::make();

        return $api->{$resourceName}()
            ->createOrUpdate(attributes: $data);
    }
}
