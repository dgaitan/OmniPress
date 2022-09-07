<?php

namespace App\Actions\Memberships;

use App\Models\Membership;
use App\Services\QueryService;
use InvalidArgumentException;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckMembershipAction
{
    use AsAction;

    /**
     * Handle Membership Renewals.
     *
     * IT will be able to renew one single membership or loop
     * through the whole memberships.
     *
     * @param  bool  $allMembership - By default it loops all memberships
     * @param  Membership|null  $membership - Define a membership if will renew one single membership
     * @return void
     *
     * @throws InvalidArgumentException if membership is null and is not looping through all memberships
     */
    public function handle(
        bool $allMembership = true,
        Membership|null $membership = null
    ): void {
        if (! $allMembership && is_null($membership)) {
            throw new InvalidArgumentException(
                sprintf(
                    'A Membership is required when trying to renew one single membership at %s',
                    self::class
                )
            );
        }

        $allMembership
            ? $this->handleAllRenewals()
            : $this->handleSingleRenewal(membership: $membership);
    }

    /**
     * Handle renewal for all memberships.
     *
     * @return void
     */
    protected function handleAllRenewals(): void
    {
        $query = Membership::with('customer', 'kindCash');
        QueryService::walkTrough($query, function ($membership) {
            $this->handleSingleRenewal(membership: $membership);
        });
    }

    /**
     * Handle one single renewal
     *
     * @param  Membership  $membership
     * @return void
     */
    protected function handleSingleRenewal(Membership $membership): void
    {
        if ($membership->isExpired()) {
            return;
        }

        // If the membership is active, send reminders
        // or maybe renew it.
        if ($membership->isActive()) {
            $membership->maybeSendRenewalReminder();

            if ($membership->expireToday()) {
                $membership->maybeRenew(force: false);
            }
        }

        // If is in renewal, it means that the first renewal failed
        // So we're going to make another intent.
        if ($membership->isInRenewal()) {
            $membership->maybeRenewIfExpired(force: false);
        }

        if (
            ($membership->isCancelled() && $membership->isExpired())
            || ($membership->isInRenewal() && $membership->daysExpired() > 30)
        ) {
            $membership->expire('Membership expired because was impossible find a payment method in 30 days.');
        }

        if ($membership->isAwaitingPickGift()) {
            $membership->shipping_status = 'N/A';
            $membership->save();

            $membership->maybeRememberThatMembershipHasRenewed();

            if ($membership->daysAfterRenewal() > 30) {
                \App\Jobs\Memberships\SetDefaultGiftProductJob::dispatch($membership->id);
            }
        }
    }
}
