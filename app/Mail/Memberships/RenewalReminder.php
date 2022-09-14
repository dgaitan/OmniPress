<?php

namespace App\Mail\Memberships;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Membership
     *
     * @var Membership
     */
    protected $membership;

    /**
     * Days until renewal
     *
     * @var int
     */
    protected $days = 0;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Membership $membership, int $days)
    {
        $this->membership = $membership;
        $this->days = $days;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = $this->membership->customer->hasPaymentMethod()
            ? 'emails.memberships.renewal-reminder'
            : 'emails.memberships.renewal-reminder-without-payment';

        return $this->subject(sprintf('Your membership will renew in %s days', $this->days))
            ->view($template)
            ->with([
                'customerName' => $this->membership->customer->getFullName(),
                'days' => $this->days,
                'memberSince' => $this->membership->start_at->format('F j, Y'),
                'memberEnd' => $this->membership->end_at->format('F j, Y'),
                'kindCash' => $this->membership->kindCash->cashForHuman(),
                'accountUrl' => sprintf('%s/my-account/account-settings/', env('CLIENT_DOMAIN', 'https://kindhumans.com')),
                'paymentMethodsUrl' => sprintf('%s/my-account/payment-methods/', env('CLIENT_DOMAIN', 'https://kindhumans.com'))
            ]);
    }
}
