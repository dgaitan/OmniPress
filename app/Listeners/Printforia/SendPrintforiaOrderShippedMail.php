<?php

namespace App\Listeners\Printforia;

use App\Mail\Printforia\OrderShipped;
use App\Events\PrintforiaOrderWasShipped;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPrintforiaOrderShippedMail
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
        Mail::to($event->order->ship_to_address->email)
                    ->queue(new OrderShipped($event->order));
    }
}
