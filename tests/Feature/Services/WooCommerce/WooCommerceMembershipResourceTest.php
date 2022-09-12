<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Models\Membership;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Support\Facades\Http;

$testsGroup = 'services';

beforeEach(function () {
    $this->disableScout();
    $this->prepareMembership();
    $this->requestNewMembership();
});

it('should update kind cash value in the store', function () {
    Http::fake([
        $this->getUrl(endpoint: 'kindhumans-memberships/1/update-kind-cash') => Http::response(
            body: ['kind_cash' => 500],
            status: 200,
        )
    ]);

    $api = WooCommerceService::make();

    $membership = Membership::find(1);
    $membership->addCash(cash: 500, addedBy: 'me@me.com', override: true);

    $response = $api->memberships()->updateClientKindCash($membership);

    $this->assertTrue($response->ok());
    $this->assertEquals(200, $response->status());
    $this->assertEquals($response->json(), [
        'kind_cash' => 500
    ]);
})->group($testsGroup);

it('should fails when a membership id fails', function () {
    Http::fake([
        $this->getUrl(endpoint: 'kindhumans-memberships/1/update-kind-cash') => Http::response(
            body: ['message' => 'Invalid Membership ID. Customer is not owner of the membership'],
            status: 403,
        )
    ]);

    $api = WooCommerceService::make();

    $membership = Membership::find(1);
    $membership->addCash(cash: 500, addedBy: 'me@me.com', override: true);

    $response = $api->memberships()->updateClientKindCash($membership);

    $this->assertFalse($response->ok());
    $this->assertEquals(403, $response->status());
    $this->assertEquals($response->json(), [
        'message' => 'Invalid Membership ID. Customer is not owner of the membership'
    ]);
})->group($testsGroup);
