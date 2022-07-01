<?php

namespace App\Jobs\Printforia;

use App\Models\Printforia\PrintforiaOrder;
use App\Services\Printforia\ProcessPrintforiaOrder;
use App\Services\QueryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckPrintforiaOrdersJob implements ShouldQueue
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
        QueryService::walkTrough(
            PrintforiaOrder::whereIn('status', ['unapproved', 'approved', 'in-progress']),
            function ($printforiaOrder) {
                ProcessPrintforiaOrder::dispatchWithoutValidations($printforiaOrder->order, $printforiaOrder);
                SyncOrdersIfCompleted::dispatch($printforiaOrder);
            }
        );

    }
}
