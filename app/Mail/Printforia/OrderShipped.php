<?php

namespace App\Mail\Printforia;

use App\Models\Printforia\PrintforiaOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(protected PrintforiaOrder $order)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your order has shipped")
            ->view('emails.printforia.order-shipped')
            ->with([
                'order' => $this->order,
                'shippingAddress' => $this->order->shippingAddress(),
                'orderItems' => $this->order->getItemsAsWooItems()
            ]);
    }
}
