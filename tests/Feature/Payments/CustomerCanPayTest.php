<?php

namespace Tests\Feature\Payments;

use Laravel\Cashier\Payment;

$testsGroup = 'payments';

beforeEach(function () {
    $this->setupStripeTests();
});

it('should process charges for giving customer', function () {
    $customer = $this->createCustomer('customer_can_be_charged');
    $customer->createAsStripeCustomer();

    $response = $customer->charge(1000, 'pm_card_visa');

    $this->assertInstanceOf(Payment::class, $response);
    $this->assertEquals(1000, $response->rawAmount());
    $this->assertEquals($customer->stripe_id, $response->customer);
})->group($testsGroup);

it('should fails when customer does not have stripe customer', function () {
    $customer = $this->createCustomer('non_stripe_customer_can_be_charged');

    $response = $customer->charge(1000, 'pm_card_visa');

    $this->assertInstanceOf(Payment::class, $response);
    $this->assertEquals(1000, $response->rawAmount());
    $this->assertNull($response->customer);
})->group($testsGroup);
