<?php

namespace App\Actions\Memberships;

use App\Models\Membership;
use App\Services\WooCommerce\WooCommerceService;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateClientKindCashAction
{
    use AsAction;

    /**
     * Update KindCash on store
     *
     * @param  Membership  $membership
     * @return Membership|bool
     */
    public function handle(Membership $membership): Membership|bool
    {
        $api = WooCommerceService::make();

        try {
            $response = $api->memberships()->updateClientKindCash(membership: $membership);

            if ($response->ok()) {
                $membership->logs()->create([
                    'description' => sprintf('Client KindCash updated to %s', $response->json()['kind_cash']),
                ]);
            }

            return $membership;
        } catch (Throwable $e) {
            $membership->logs()->create([
                'description' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
