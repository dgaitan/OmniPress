<?php

namespace App\Jobs\Memberships;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SingleWooCommerceSync;
use App\Mail\Memberships\WelcomeMail;

class NewMembershipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customerId;
    protected $customerEmail;
    protected $orderId;
    protected $giftProductId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $customerEmail, int $customerId, int $orderId, int|null $giftProductId = null)
    {   
        $this->customerEmail = $customerEmail;
        $this->customerId = $customerId;
        $this->orderId = $orderId;
        $this->giftProductId = $giftProductId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->customerEmail)->send(new WelcomeMail);

        if (!is_null($this->giftProductId)) {
            SingleWooCommerceSync::dispatch($this->giftProductId, 'products');
        }

        SingleWooCommerceSync::dispatch($this->customerId, 'customers');
        SingleWooCommerceSync::dispatch($this->orderId, 'orders');
    }
}
