<?php

namespace App\Observers;

use App\Models\Printforia\PrintforiaOrder;

class PrintforiaOrderObserver
{
    /**
     * Handle the PrintforiaOrder "created" event.
     *
     * @param  \App\Models\Printforia\PrintforiaOrder  $printforiaOrder
     * @return void
     */
    public function created(PrintforiaOrder $printforiaOrder)
    {
        //
    }

    /**
     * Handle the PrintforiaOrder "updated" event.
     *
     * @param  \App\Models\Printforia\PrintforiaOrder  $printforiaOrder
     * @return void
     */
    public function updated(PrintforiaOrder $printforiaOrder)
    {
        //
    }

    /**
     * Handle the PrintforiaOrder "deleted" event.
     *
     * @param  \App\Models\Printforia\PrintforiaOrder  $printforiaOrder
     * @return void
     */
    public function deleted(PrintforiaOrder $printforiaOrder)
    {
        //
    }

    /**
     * Handle the PrintforiaOrder "restored" event.
     *
     * @param  \App\Models\Printforia\PrintforiaOrder  $printforiaOrder
     * @return void
     */
    public function restored(PrintforiaOrder $printforiaOrder)
    {
        //
    }

    /**
     * Handle the PrintforiaOrder "force deleted" event.
     *
     * @param  \App\Models\Printforia\PrintforiaOrder  $printforiaOrder
     * @return void
     */
    public function forceDeleted(PrintforiaOrder $printforiaOrder)
    {
        //
    }
}
