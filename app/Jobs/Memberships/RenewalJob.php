<?php

namespace App\Jobs\Memberships;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RenewalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $everything = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(bool $everything = false)
    {
        $this->everything = $everything;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Retrieve the memberships in a range of 2 month in the pase and future from today.
        Cache::tags('memberships')->flush();
        $currentPage = 1;
        $hasMorePages = true;
        $items = [];
        $memberships = Membership::with(['customer', 'kindCash']);

        // if (! $this->everything) {
        //     $aMonthAgo = Carbon::now()->subMonth();
        //     $oneMonth = Carbon::now()->addMonth();
        //     $memberships = $memberships->whereBetween('end_at', [$aMonthAgo, $oneMonth]);            
        // }

        if ($memberships->exists()) {
            $query = $memberships->paginate(50);
            $items = $query->items();
            $mailQueue = 0;

            // While the query has more page, keep running it
            while ($hasMorePages) {

                foreach ($items as $key => $membership) {
                    // If the membership is active, send reminders
                    // or maybe renew it.
                    if ($membership->isActive()) {

                        $membership->maybeSendRenewalReminder($mailQueue++);

                        if ($membership->expireToday()) {
                            $membership->maybeRenew(force: false, index: $mailQueue++);
                        }
                    }

                    // If is in renewal, it means that the first renewal failed
                    // So we're going to make another intent.
                    if ($membership->isInRenewal()) {
                        $membership->maybeRenewIfExpired($this->everything, $mailQueue++);
                    }

                    if (($membership->isInRenewal() || $membership->isCancelled()) && $membership->daysExpired() > 30) {
                        $membership->expire("Membership expired because was impossible find a payment method in 30 days.");
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

                // Increment the page
                $currentPage = $currentPage + 1;
                Paginator::currentPageResolver(function() use ($currentPage) {
                    return $currentPage;
                });

                // Initialize a new instance.
                $query = $memberships->paginate(50);
                $hasMorePages = $query->count() > 0;
                $items = $query->items();
            }

        }
    }
}
