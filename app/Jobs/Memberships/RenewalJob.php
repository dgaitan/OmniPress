<?php

namespace App\Jobs\Memberships;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Membership;

class RenewalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $aMonthAgo = Carbon::now()->subMonth();
        $oneMonth = Carbon::now()->addMonth();
        $memberships = Membership::with(['customer', 'kindCash'])
            ->whereBetween('end_at', [$aMonthAgo, $oneMonth]);

        if ($memberships->exists()) {
            $memberships = $memberships->paginate(50);
            
            while ($memberships->hasMorePages()) {
                
                foreach ($memberships->items() as $membership) {
                
                    if ($membership->isActive()) {
                
                        if (in_array($membership->daysUntilRenewal(), array( 15, 5, 3 ) )) {
                            $membership->sendRenewalReminder();
                        }

                        if ($membership->daysUntilRenewal() === 0) {

                        }
                    }
                }

                $memberships->nextCursor();
            }
        }
    }
}
