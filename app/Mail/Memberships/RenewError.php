<?php

namespace App\Mail\Memberships;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewError extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Membership isntance
     *
     * @var Membership
     */
    protected Membership $membership;

    /**
     * Error message
     *
     * @var string
     */
    protected string $message = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Membership $membership, string $message = '')
    {
        $this->membership = $membership;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('We got an error during your kindhumans membership renewal.')
            ->view('emails.memberships.renew-error')
            ->with([
                'customerName' => $this->membership->customer->getFullName(),
                'paymentMethodUrl' => sprintf('%s/my-account/payment-methods/', env('CLIENT_DOMAIN', 'https://kindhumans.com')),
                'errorMessage' => $this->message,
            ]);
    }
}
