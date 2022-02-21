<?php

namespace App\Mail\Memberships;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MembershipRenewed extends Mailable
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
        $subject = sprintf(
            "Hello %s, Your Kindhumans Membership has been Renewed! 🥳",
            $this->membership->customer->getfullName()
        );

        return $this->subject($subject)
            ->view('emails.memberships.renewed')
            ->with([
                'customerName' => $this->membership->customer->getFullName(),
                'pickGiftUrl' => sprintf(
                    '%s/my-account/membership/pick-your-hat/%s',
                    env('CLIENT_DOMAIN', 'https://kindhumans.com'),
                    $this->membership->id
                ),
                'accountSettingsUrl' => sprintf(
                    '%s/my-account/account-settings/',
                    env('CLIENT_DOMAIN', 'https://kindhumans.com')
                ),
                'memberSince' => $this->membership->start_at->format('F j, Y'),
                'memberEnds' => $this->membership->end_at->format('F j, Y'),
                'kindCash' => $this->membership->kindCash->cashForHuman()
            ]);
    }
}
