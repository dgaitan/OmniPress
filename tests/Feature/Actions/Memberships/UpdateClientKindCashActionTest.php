<?php

namespace Tests\Feature\Actions\Memberships;

use App\Actions\Memberships\UpdateClientKindCashAction;
use App\Models\Membership;
use Illuminate\Support\Facades\Http;

$testsGroup = 'kindcash';

beforeEach(function () {
    $this->disableScout();
    $this->prepareMembership();
    $this->requestNewMembership();
});

it('should update membership kind cash on kindhumans store', function () {
    Http::fake([
        $this->getUrl(endpoint: 'kindhumans-memberships/1/update-kind-cash') => Http::response(
            body: ['kind_cash' => 500],
            status: 200,
        ),
    ]);

    $membership = Membership::find(1);
    $membership->addCash(cash: 500, addedBy: 'me@me.com', override: true);

    $result = UpdateClientKindCashAction::run(membership: $membership);

    $this->assertInstanceOf(Membership::class, $result);
})->group($testsGroup);
