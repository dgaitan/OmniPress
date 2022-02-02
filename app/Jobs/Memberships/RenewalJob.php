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
        $memberships = Membership::whereBetween('end_at', [$aMonthAgo, $oneMonth])->get();

        if (!is_null($memberships)) {
            
        }
    }
}
