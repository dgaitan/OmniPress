<?php

namespace App\Actions\Memberships;

use App\Models\Membership;
use App\Services\WooCommerce\WooCommerceService;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateClientKindCashAction
{
    use AsAction;

    public function handle(Membership $membership): Membership
    {
        $api = WooCommerceService::make();

        try {
            $response = $api->memberships()->updateClientKindCash(membership: $membership);

            if ($response->ok()) {
                $membership->logs()->create([
                    'description' => sprintf('Client KindCash updated to %s', $response->json()['kind_cash'])
                ]);
            }
        } catch (Throwable $e) {
            $membership->logs()->create([
                'description' => $e->getMessage()
            ]);
        }

        return $membership;
    }
}
