<?php

namespace App\Mail\Memberships;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MembershipExpired extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Membership Instance
     *
     * @var Membership
     */
    protected Membership $membership;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        Membership $membership,
    ) {
        $this->membership = $membership;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("We are sorry to see you go!")
            ->view('emails.memberships.expired')
            ->with([
                'shopPage' => sprintf(
                    '%s/shop',
                    env('CLIENT_DOMAIN', 'https://kindhumans.com')
                ),
                'loginPage' => sprintf(
                    '%s/login',
                    env('CLIENT_DOMAIN', 'https://kindhumans.com')
                )
            ]);
    }
}
