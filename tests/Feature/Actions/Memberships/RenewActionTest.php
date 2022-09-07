<?php

namespace Tests\Feature\Actions\Membership;

use App\Actions\Memberships\RenewAction;
use App\Mail\Memberships\MembershipExpired;
use App\Mail\Memberships\PaymentNotFound;
use App\Models\Membership;
use App\Models\WooCommerce\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

$testsGroup = 'memberships';

beforeEach(function () {
    $this->disableScout();
    $this->prepareMembership();
    $this->requestNewMembership();
});

it('should not renew because membership is not expired', function () {
    $membership = Membership::find(1);
    $result = RenewAction::run(membership: $membership);

    $this->assertIsString($result);

    $this->assertEquals(
        "Membership with ID #1 isn't expired.",
        $result
    );

    $this->assertEquals(
        "Renewal Error: Membership with ID #1 isn't expired.",
        $membership->logs->last()->description
    );
})->group($testsGroup);

it('should fails because customer has not payment method', function () {
    Mail::fake();

    $membership = Membership::find(1);
    $membership->end_at = Carbon::now();
    $membership->save();
    $result = RenewAction::run(membership: $membership);

    $this->assertIsString($result);
    $this->assertEquals("Mebership renewal failed because we wasn't able to find a payment method for the customer", $result);

    Mail::assertQueued(PaymentNotFound::class);

    $this->assertEquals(Membership::IN_RENEWAL_STATUS, $membership->status);
    $this->assertEquals('N/A', $membership->shipping_status);
})->group($testsGroup);

it('should be expired because customer has not payment method and membership got expired more than 30 days ago', function () {
    Mail::fake();

    $membership = Membership::find(1);
    $membership->end_at = Carbon::now()->subDays(33);
    $membership->save();
    $result = RenewAction::run(membership: $membership);

    $this->assertIsString($result);
    $this->assertEquals('Membership expired because was impossible find a payment method in 30 days.', $result);

    Mail::assertQueued(MembershipExpired::class);

    $membership = Membership::find(1);
    $this->assertTrue($membership->isExpired());
    $this->assertEquals(Membership::SHIPPING_CANCELLED_STATUS, $membership->shipping_status);
})->group($testsGroup);

it('should not renew if membership has an invalid status', function () {
    // $customer = Customer::whereEmail('ram@ram.com')->first();
    // $customer->createAsStripeCustomer();

    $membership = Membership::find(1);
    $membership->end_at = Carbon::now();
    $membership->status = Membership::AWAITING_PICK_GIFT_STATUS;
    $membership->save();
    $result = RenewAction::run(membership: $membership);

    $this->assertIsString($result);
    $this->assertEquals(
        'Membership must be active or in-renewal to be able to create another order',
        $result
    );
})->group($testsGroup);
