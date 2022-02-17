<?php

namespace App\Mail\Memberships;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Membership;

class PaymentNotFound extends Mailable
{
    use Queueable, SerializesModels;

    protected Membership $membership;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Membership $membership)
    {
        $this->membership = $membership;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("We can not find a payment method for your kindhumans membership renewal.")
            ->view('emails.memberships.payment-not-found')
            ->with([
                'customerName' => $this->membership->customer->getFullName(),
                'renewUrl' => sprintf('%s/my-account/membership/renew', env('https://kind.humans', 'https://kindhumans.com')),
            ]);
    }
}
