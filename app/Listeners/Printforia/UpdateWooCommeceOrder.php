<?php

namespace App\Listeners\Printforia;

use App\Events\PrintforiaOrderWasShipped;
use App\Jobs\Printforia\SyncOrdersIfCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateWooCommeceOrder
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PrintforiaOrderWasShipped  $event
     * @return void
     */
    public function handle(PrintforiaOrderWasShipped $event)
    {
        SyncOrdersIfCompleted::dispatch($event->order)->delay(now()->addSeconds(10));
    }
}
