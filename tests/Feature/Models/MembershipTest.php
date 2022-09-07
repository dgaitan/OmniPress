<?php

namespace Tests\Feature\Models;

use App\Mail\Memberships\RenewalReminder;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

$testsGroup = 'memberships';

beforeEach(function () {
    $this->disableScout();
    $this->prepareMembership();
    $this->requestNewMembership();
});

test('Membership should be active and range of dates must be of one year after creatomg a membership', function () {
    $membership = Membership::find(1);

    $this->assertTrue($membership->isActive());
    $this->assertFalse($membership->IsInRenewal());
    $this->assertFalse($membership->isAwaitingPickGift());
    $this->assertFalse($membership->isCancelled());
    $this->assertFalse($membership->isExpired());

    // Test days flag methods
    $this->assertEquals(365, $membership->daysUntilRenewal());
    $this->assertEquals(0, $membership->daysExpired());
    $this->assertFalse($membership->expireToday());
})->group($testsGroup);

it('should send renewal reminder when is soon to expire', function () {
    Mail::fake();

    $membership = Membership::find(1);
    $membership->end_at = Carbon::now()->addMonth();
    $membership->save();

    // 30 days is not soon to expire yet
    $this->assertEquals(30, $membership->daysUntilRenewal());

    $membership->end_at = Carbon::now()->addDays(15);
    $membership->save();

    // Now it must send renewal notifaction
    $this->assertEquals(15, $membership->daysUntilRenewal());

    $this->assertTrue($membership->maybeSendRenewalReminder());
    Mail::assertQueued(RenewalReminder::class);

    $this->assertEquals(
        "15 days email reminder was sent to customer.",
        $membership->logs->last()->description
    );

    // Now we are going to test when membership has 5 days until expiration.
    $membership = Membership::find(1);
    $membership->end_at = Carbon::now()->addDays(5);
    $membership->save();

    $this->assertEquals(5, $membership->daysUntilRenewal());
    $this->assertTrue($membership->maybeSendRenewalReminder());

    Mail::assertQueued(RenewalReminder::class);

    $this->assertEquals(
        "5 days email reminder was sent to customer.",
        $membership->logs->last()->description
    );

    // Now we are going to test when membershp has 3 days to expire
    $membership = Membership::find(1);
    $membership->end_at = Carbon::now()->addDays(3);
    $membership->save();

    $this->assertEquals(3, $membership->daysUntilRenewal());
    $this->assertTrue($membership->maybeSendRenewalReminder());

    Mail::assertQueued(RenewalReminder::class);

    $this->assertEquals(
        "3 days email reminder was sent to customer.",
        $membership->logs->last()->description
    );

    // After 3 days we should not send anything.
    $membership = Membership::find(1);
    $membership->end_at = Carbon::now()->addDays(2);
    $membership->save();

    $this->assertEquals(2, $membership->daysUntilRenewal());
    $this->assertFalse($membership->maybeSendRenewalReminder());

})->group($testsGroup);

it('should mark as expired when end date is today', function () {
    $membership = Membership::find(1);
    $membership->end_at = Carbon::now()->addDays(2);
    $membership->save();

    // Membership expireToday flag should be false yet
    $this->assertFalse($membership->expireToday());

    $membership->update(['end_at' => Carbon::now()]);

    $this->assertTrue($membership->expireToday());
})->group($testsGroup);
