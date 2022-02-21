<?php

namespace App\Jobs\Memberships;

use App\Models\Membership;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetDefaultGiftProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $membership_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $membership_id)
    {
        $this->membership_id = $membership_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $membership = Membership::find($this->membership_id);

        if (! is_null($membership)) {
            $order_id = $membership->orders()->first()->order_id;
            $api = WooCommerceService::make();
            $request = $api->memberships()->addGiftProduct($order_id);

            if ($request) {
                $membership->status = Membership::ACTIVE_STATUS;
                $membership->save();
            }
        }
    }
}
