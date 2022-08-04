<?php

namespace App\Listeners\Printforia;

use App\Events\PrintforiaOrderWasShipped;
use App\Mail\Printforia\OrderShipped;
use Illuminate\Support\Facades\Mail;

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
        // Mail::to($event->order->ship_to_address->email)
        //             ->queue(new OrderShipped($event->order));
    }
}
