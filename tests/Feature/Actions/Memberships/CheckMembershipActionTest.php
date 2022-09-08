<?php

namespace Tests\Feature\Actions;

use App\Actions\Memberships\CheckMembershipAction;
use App\Mail\Memberships\MembershipRenewed;
use App\Mail\Memberships\RenewalReminder;
use App\Models\Membership;
use App\Models\WooCommerce\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

$testsGroup = 'memberships';

beforeEach(function () {
    $this->disableScout();
    $this->prepareMembership();
    $this->requestNewMembership();
});

it('should not does anything because membership is expired', function () {
    Queue::fake();

    $membership = Membership::find(1);
    $membership->update(['status' => Membership::EXPIRED_STATUS]);

    CheckMembershipAction::run(allMembership: false, membership: $membership);

    $updatedMembership = Membership::find(1);
    $this->assertTrue($updatedMembership->isExpired());
    $this->assertEquals(
        $updatedMembership->start_at,
        $membership->start_at
    );
    $this->assertEquals(
        $updatedMembership->end_at,
        $membership->end_at
    );
    $this->assertEquals(
        $updatedMembership->last_payment_intent,
        $membership->last_payment_intent
    );

    Queue::assertNothingPushed();
})->group($testsGroup);

it('should send a renewal reminder', function () {
    Queue:fake();
    Mail::fake();

    $membership = Membership::find(1);
    $membership->update([
        'end_at' => now()->addDays(15)
    ]);

    CheckMembershipAction::run(allMembership: false, membership: $membership);

    $updatedMembership = Membership::find(1);
    $this->assertTrue($updatedMembership->isActive());
    $this->assertEquals(15, $updatedMembership->daysUntilRenewal());

    Mail::assertQueued(RenewalReminder::class);
})->group($testsGroup);

it('should try to renew but will fails because customer does not have payment method', function () {
    Mail::fake();
    Queue::fake();

    $this->fakeOrderCreation();

    $membership = Membership::find(1);
    $membership->update([
        'end_at' => now()
    ]);

    CheckMembershipAction::run(allMembership: false, membership: $membership);

    $updatedMembership = Membership::find(1);
    $this->assertTrue($updatedMembership->isInRenewal());
    $this->assertEquals(
        "Renewal Error: Mebership renewal failed because we wasn't able to find a payment method for the customer",
        $updatedMembership->logs->last()->description
    );
})->group($testsGroup);

it('should renew a membership', function () {
    Mail::fake();
    Queue::fake();

    $this->fakeOrderCreation();

    $customer = Customer::whereEmail('ram@ram.com')->first();
    $customer->createAsStripeCustomer();
    $this->assignPaymentMethodToCustomer(customer: $customer);

    $membership = Membership::find(1);
    $membership->update([
        'end_at' => now()
    ]);

    CheckMembershipAction::run(allMembership: false, membership: $membership);

    $updatedMembership = Membership::find(1);
    $this->assertTrue($updatedMembership->isAwaitingPickGift());
    $this->assertEquals('N/A', $updatedMembership->shipping_status);
    $this->assertEquals(
        now()->format('Y-m-d'),
        $updatedMembership->last_payment_intent->format('Y-m-d')
    );
    $this->assertTrue($updatedMembership->daysAfterRenewal() === 0);

    Mail::assertQueued(MembershipRenewed::class);
})->group($testsGroup);
