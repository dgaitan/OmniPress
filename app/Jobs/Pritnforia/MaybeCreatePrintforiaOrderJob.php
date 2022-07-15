<?php

namespace App\Jobs\Pritnforia;

use App\Actions\Printforia\MaybeCreatePrintforiaOrderAction;
use App\Models\WooCommerce\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MaybeCreatePrintforiaOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Order $order)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        MaybeCreatePrintforiaOrderAction::run($this->order);
    }
}
