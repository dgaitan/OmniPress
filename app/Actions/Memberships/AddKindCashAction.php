<?php

namespace App\Actions\Memberships;

use App\Models\Membership;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class AddKindCashAction
{
    use AsAction;

    /**
     * Action to add kindcash to an existing Membership
     *
     * @param Membership $membership - The membership to apply kind cash
     * @param int $cash - The cash to add.
     * @param bool $override - it means that it will update the entire cash. Otherwise it will add the cash.
     * @return void
     */
    public function handle(
        Membership $membership,
        int|float|string $cash = 0,
        bool $override = false,
        string|null $addedBy
    ): Membership
    {
        // Expired memberships can't collect cash
        if ($membership->isExpired()) {
            return $membership;
        }

        // Convert the amount in $$ to cents
        $cash = (int) ((float) $cash * 100);
        $message = "Kind Cash added.";

        if (! is_null($addedBy)) {
            $message = sprintf(
                'Kind Cash added by %s',
                $addedBy
            );
        }

        if (! $override) {
            $membership->kindCash->update([
                'last_earned' => $cash
            ]);
        }

        $cash = $override
            ? $cash
            : $membership->kindCash->points + $cash;

        $membership->kindCash->update([
            'points' => $cash,
        ]);

        $membership->kindCash->addLog('earned', $cash, $message);

        Cache::tags('memberships')->flush();

        return $membership;
    }
}
