<?php

namespace Tests\Feature\Actions\Membership;

use App\Actions\Memberships\AddKindCashAction;
use App\Models\Membership;

$testsGroup = 'kindcash';

beforeEach(function () {
    $this->disableScout();
    $this->prepareMembership();
    $this->requestNewMembership();
});

it('should add kind cash to a membership', function () {
    $membership = Membership::find(1);

    // Checking current kindcash
    $this->assertEquals(750, $membership->kindCash->points);

    $result = AddKindCashAction::run(
        membership: $membership,
        cash: 100, // it means $100
        override: false,
        addedBy: 'dgaitan@kindhumans.com'
    );

    $this->assertEquals(10750, $result->kindCash->points);
    $this->assertEquals('$107.50', $result->kindCash->getCash()->format());

    $result = AddKindCashAction::run(
        membership: $membership,
        cash: 5, // it means $5
        override: false,
        addedBy: 'dgaitan@kindhumans.com'
    );

    $this->assertEquals(11250, $result->kindCash->points);
    $this->assertEquals('$112.50', $result->kindCash->getCash()->format());

    $result = AddKindCashAction::run(
        membership: $membership,
        cash: 0.50, // it means $0.50
        override: false,
        addedBy: 'dgaitan@kindhumans.com'
    );

    $this->assertEquals(11300, $result->kindCash->points);
    $this->assertEquals('$113.00', $result->kindCash->getCash()->format());

    $result = AddKindCashAction::run(
        membership: $membership,
        cash: 50, // it means $50
        override: true,
        addedBy: 'dgaitan@kindhumans.com'
    );

    $this->assertEquals(5000, $result->kindCash->points);
    $this->assertEquals('$50.00', $result->kindCash->getCash()->format());
})->group($testsGroup);
