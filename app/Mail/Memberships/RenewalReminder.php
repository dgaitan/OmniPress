<?php

namespace App\Mail\Memberships;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Membership;

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
     * @var integer
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

        return $this->subject(sprintf("Your membership will renew in %s days", $this->days))
            ->view('emails.memberships.renewal-reminder')
            ->with([
                'customerName' => $this->membership->customer->getFullName(),
                'days' => $this->days,
                'memberSince' => $this->membership->start_at->format('F j, Y'),
                'memberEnd' => $this->membership->end_at->format('F j, Y'),
                'kindCash' => $this->membership->kindCash->cashForHuman(),
                'accountUrl' => sprintf('%s/my-account/account-settings/', env('CLIENT_DOMAIN', 'https://kindhumans.com')),
            ]);
    }
}
