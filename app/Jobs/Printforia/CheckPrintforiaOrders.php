<?php

namespace App\Jobs\Printforia;

use App\Models\Printforia\PrintforiaOrder;
use App\Services\Printforia\PrintforiaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckPrintforiaOrders implements ShouldQueue
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
        $offset = 0;
        $perPage = 100;
        $page = 1;
        $printforiaOrderes = PrintforiaOrder::whereIn('status', ['unapproved', 'approved', 'in-progress'])
            ->skip($offset)->take($perPage)->get();

        while ($printforiaOrderes->isNotEmpty()) {
            $page++;

            $printforiaOrderes->map(function ($order) {
                $order = PrintforiaService::updatePrintforiaOrder($order);
                SyncOrdersIfCompleted::dispatch($order);
            });

            $offset = $offset + $perPage;
            $printforiaOrderes = PrintforiaOrder::whereIn('status', ['unapproved', 'approved', 'in-progress'])
                ->skip($offset)->take($perPage)->get();
        }
    }
}
