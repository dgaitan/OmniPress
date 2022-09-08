<?php

namespace Tests\Utils;

use App\Models\WooCommerce\Customer;
use Laravel\Cashier\Cashier;
use Stripe\StripeClient;

trait InteractsWithStripe
{
    public function setupStripeTests(): void
    {
        if (! getenv('STRIPE_SECRET')) {
            $this->markTestSkipped('Stripe secret key not set.');
        }
    }

    protected static function stripe(array $options = []): StripeClient
    {
        return Cashier::stripe(
            array_merge(['api_key' => getenv('STRIPE_SECRET')], $options)
        );
    }

    protected function createCustomer($description = 'taylor', array $options = []): Customer
    {
        return Customer::create(array_merge([
            'customer_id' => 2064,
            'username' => 'David',
            'first_name' => 'John',
            'last_name' => 'Bar',
            'email' => "{$description}@cashier-test.com",
            'billing' => '{}',
            'shipping' => '{}',
            'is_paying_customer' => false,
        ], $options));
    }

    protected function assignPaymentMethodToCustomer(Customer $customer): Customer
    {
        $paymentMethod = $customer->addPaymentMethod('pm_card_visa');
        $customer->updateDefaultPaymentMethod($paymentMethod->id);

        return $customer;
    }
}
