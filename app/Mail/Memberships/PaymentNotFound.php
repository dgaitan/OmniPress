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

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

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
                'paymentMethodUrl' => sprintf('%s/my-account/payment-methods/', env('CLIENT_DOMAIN', 'https://kindhumans.com')),
            ]);
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(5);
    }
}
