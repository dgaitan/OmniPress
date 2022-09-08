<?php

namespace Tests\Feature\Actions;

use App\Actions\Memberships\RenewAction;
use App\Actions\Memberships\SetDefaultProductAction;
use App\Jobs\SingleWooCommerceSync;
use App\Models\Membership;
use App\Models\WooCommerce\Customer;
use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

$testsGroup = 'memberships';

beforeEach(function () {
    $this->disableScout();
    $this->prepareMembership();
    $this->requestNewMembership();

    Mail::fake();
    Queue::fake();

    $this->fakeOrderCreation();

    $customer = Customer::whereEmail('ram@ram.com')->first();
    $customer->createAsStripeCustomer();
    $this->assignPaymentMethodToCustomer(customer: $customer);

    $membership = Membership::find(1);
    $membership->end_at = Carbon::now();
    $membership->save();

    // Run renewal
    $result = RenewAction::run(membership: $membership);
});

it('should set a new membership when the passed 30 days after auto-renewal', function () {
    Queue::fake([
        SingleWooCommerceSync::class
    ]);

    Http::fake([
        $this->getUrl(endpoint: 'kindhumans-memberships/549800/set-gift') => function (Request $request) {
            if ($request->method() === 'PUT') {
                return Http::response(
                    body: [
                        'product_id' => 52020,
                        'order_id' => 549800
                    ],
                    status: 200
                );
            }
        },
        $this->getUrl(endpoint: 'products/52020') => Http::response(
            body: $this->fixture('Memberships/GiftProductPicker'),
            status: 200
        ),
        $this->getUrl(endpoint: 'orders/549800') => Http::response(
            body: $this->fixture('Memberships/OrderWithGiftProduct'),
            status: 200
        )
    ]);

    $membership = Membership::find(1);

    // The user was renewed. So now it must be on AwaitingPickGift
    $this->assertTrue($membership->isAwaitingPickGift());

    // Let's simulate that has been passed 30 days to run the SetDefaultProductAction
    $membership->update(['last_payment_intent' => Carbon::now()->subMonths(2)]);

    $this->assertTrue($membership->daysAfterRenewal() > 30);

    $result = SetDefaultProductAction::run(membership: $membership);

    $this->assertInstanceOf(Membership::class, $result);
    $this->assertTrue($result->isActive());
    $this->assertEquals(Membership::SHIPPING_PENDING_STATUS, $result->shipping_status);

    $order = $membership->orders()->first();
    // $this->assertEquals(2, $order->items->count());
    $this->assertEquals(549800, $order->order_id);

    $this->assertEquals(52020, $result->gift_product_id);

    Queue::assertPushed(SingleWooCommerceSync::class);
})->group($testsGroup);
