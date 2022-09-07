<?php

namespace Tests\Utils;

use App\Models\User;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\PaymentMethod;
use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Support\Facades\Http;

trait InteractsWithMemberships
{
    public function prepareMembership(): void
    {
        PaymentMethod::create([
            'payment_method_id' => 'kindhumans_stripe_gateway',
            'title' => 'Credit Card',
            'order' => 1,
            'enabled' => true,
            'method_title' => 'Kindhumans Payment Gateway',
        ]);

        Customer::create([
            'customer_id' => 2064,
            'email' => 'ram@ram.com',
            'first_name' => 'John',
            'last_name' => 'Bar',
            'role' => 'customer',
            'username' => 'smlueker',
            'billing' => '{}',
            'shipping' => '{}',
            'is_paying_customer' => false,
        ]);

        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('products/544443') => Http::response(
                body: $this->fixture('Memberships/GiftProduct'),
                status: 200
            ),
            $this->getUrl('products/160768') => Http::response(
                body: $this->fixture('Memberships/MembershipProduct'),
                status: 200
            ),
        ]);

        $api->products()->getAndSync(160768);
        $api->products()->getAndSync(544443);

        Http::fake([
            $this->getUrl(endpoint: 'orders/549799') => Http::response(
                body: $this->fixture('Memberships/Order'),
                status: 200
            ),
            $this->getUrl(endpoint: 'customers/2064') => Http::response(
                body: $this->fixture('WooCommerce/CustomerDetail'),
                status: 200
            ),
        ]);

        $api->orders()->getAndSync(549799);
    }

    protected function requestNewMembership(): void
    {
        $params = [
            'price' => 3500,
            'customer_id' => 2064,
            'email' => 'ram@ram.com',
            'username' => 'David ram',
            'order_id' => 549799,
            'points' => 750,
            'gift_product_id' => 544443,
            'product_id' => 160768,
        ];

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $this->post('api/v1/memberships/new', $params);
    }
}
