<?php

namespace Tests\Feature\Http\API;

use App\Jobs\Memberships\NewMembershipJob;
use App\Models\KindCash;
use App\Models\Membership;
use App\Models\User;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\PaymentMethod;
use App\Models\WooCommerce\Product;
use App\Services\WooCommerce\WooCommerceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->disableScout();

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
});

it('new endpoint should create a new membership', function () {
    Queue::fake([
        NewMembershipJob::class
    ]);

    $customer = Customer::whereEmail('ram@ram.com')->first();
    $this->assertNotNull($customer);

    $membershipProduct = Product::whereProductId(160768)->first();
    $this->assertNotNull($membershipProduct);

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

    $response = $this->post('api/v1/memberships/new', $params);

    $response->assertOk();
    $response->assertStatus(200);

    $membership = Membership::whereCustomerId($customer->id)
        ->first();

    $response->assertJson([
        'membership' => [
            'id' => 1,
            'start_at' => $membership->start_at->toJSON(),
            'end_at' => $membership->end_at->toJSON(),
            'status' => 'active',
            'shipping_status' => 'pending'
        ],
        'kind_cash' => [
            'points' => 750,
            'last_earned' => 750
        ]
    ]);

    $this->assertEquals(3500, $membership->price);
    $this->assertEquals('ram@ram.com', $membership->customer_email);
    $this->assertEquals(
        Carbon::parse(now()->format('Y-m-d')),
        $membership->start_at
    );
    $this->assertEquals(
        Carbon::parse(now()->addYear()->format('Y-m-d')),
        $membership->end_at
    );
    $this->assertEquals('active', $membership->status);
    $this->assertEquals('pending', $membership->shipping_status);
    $this->assertEquals(0, $membership->pending_order_id);
    $this->assertTrue($membership->user_picked_gift);

    // Testing membership flags after creating membership
    $this->assertTrue($membership->isActive());
    $this->assertEquals($customer::class, $membership->customer::class);
    $this->assertEquals($customer->id, $membership->customer->id);
    $this->assertEquals($customer->customer_id, $membership->customer->customer_id);

    // Testing that membership has a kindcash and it has values
    $this->assertInstanceOf(KindCash::class, $membership->kindCash);
    $this->assertEquals(750, $membership->kindCash->points);
    $this->assertEquals(750, $membership->kindCash->last_earned);

    // Testing that order is related to the latest membership
    $order = Order::whereOrderId(549799)->first();
    $this->assertTrue($order->has_membership);
    $this->assertEquals($order->membership_id, $membership->id);
    $this->assertSame($order->order_id, $membership->getCurrentOrder()->order_id);

    /**
     * A gitftproduct is a product that comes with the membership
     * and it should be present in the request and it should be preset
     * in the order and in the membership
     */
    $giftProduct = Product::whereProductId(544443)->first();
    $this->assertSame(
        $giftProduct->product_id,
        $membership->getCurrentGiftProduct()->product_id
    );
    $this->assertEquals(
        $giftProduct->name,
        $membership->getCurrentGiftProduct()->name
    );

    Queue::assertPushed(NewMembershipJob::class);
})->group('memberships');

// class MembershipControllerTest extends BaseHttp
// {
//     use InteractsWithScout;

//     protected function setUp(): void {
//         parent::setUp();

//         $this->disableScout();
//         $this->prepareData();
//     }

//     public function test_new_endpoint_should_create_a_new_membership(): void
//     {
//         $customer = Customer::whereEmail('ram@ram.com')->first();
//         $this->assertNotNull($customer);

//         $membershipProduct = Product::whereProductId(160768)->first();
//         $this->assertNotNull($membershipProduct);

//         $params = [
//             'price' => 3500,
//             'customer_id' => 2064,
//             'email' => 'ram@ram.com',
//             'username' => 'David ram',
//             'order_id' => 549799,
//             'points' => 750,
//             'gift_product_id' => 544443,
//             'product_id' => 160768,
//         ];

//         $this->actingAs($user = User::factory()->withPersonalTeam()->create());

//         $response = $this->post('api/v1/memberships/new', $params);

//         $response->assertOk();
//         $response->assertStatus(200);

//         $membership = Membership::whereCustomerId($customer->id)
//             ->first();

//         $response->assertJson([
//             'membership' => [
//                 'id' => 1,
//                 'start_at' => $membership->start_at->toJSON(),
//                 'end_at' => $membership->end_at->toJSON(),
//                 'status' => 'active',
//                 'shipping_status' => 'pending'
//             ],
//             'kind_cash' => [
//                 'points' => 750,
//                 'last_earned' => 750
//             ]
//         ]);

//         // Testing membership flags after creating membership
//         $this->assertTrue($membership->isActive());
//         $this->assertEquals($customer, $membership->customer);

//         // Testing that membership has a kindcash and it has values
//         $this->assertInstanceOf(KindCash::class, $membership->kindCash);
//         $this->assertEquals(750, $membership->kindCash->points);
//         $this->assertEquals(750, $membership->kindCash->last_earned);

//         // Testing that order is related to the latest membership
//         $order = Order::whereOrderId(549799)->first();
//         $this->assertTrue($order->has_membership);
//         $this->assertEquals($order->membership_id, $membership->id);
//         $this->assertSame($order, $membership->getCurrentOrder());

//         /**
//          * A gitftproduct is a product that comes with the membership
//          * and it should be present in the request and it should be preset
//          * in the order and in the membership
//          */
//         $giftProduct = Product::whereProductId(544443)->first();
//         $this->assertSame($giftProduct, $membership->getCurrentGiftProduct());

//     }

//     /**
//      * Load Orders and Customer to test memberships
//      *
//      * @return void
//      */
//     protected function prepareData(): void
//     {
//         PaymentMethod::create([
//             'payment_method_id' => 'kindhumans_stripe_gateway',
//             'title' => 'Credit Card',
//             'order' => 1,
//             'enabled' => true,
//             'method_title' => 'Kindhumans Payment Gateway',
//         ]);

//         Customer::create([
//             'customer_id' => 2064,
//             'email' => 'ram@ram.com',
//             'first_name' => 'John',
//             'last_name' => 'Bar',
//             'role' => 'customer',
//             'username' => 'smlueker',
//             'billing' => '{}',
//             'shipping' => '{}',
//             'is_paying_customer' => false,
//         ]);

//         $api = WooCommerceService::make();

//         Http::fake([
//             $this->getUrl('products/544443') => Http::response(
//                 body: $this->fixture('Memberships/GiftProduct'),
//                 status: 200
//             ),
//             $this->getUrl('products/160768') => Http::response(
//                 body: $this->fixture('Memberships/MembershipProduct'),
//                 status: 200
//             ),
//         ]);

//         $api->products()->getAndSync(160768);
//         $api->products()->getAndSync(544443);

//         Http::fake([
//             $this->getUrl(endpoint: 'orders/549799') => Http::response(
//                 body: $this->fixture('Memberships/Order'),
//                 status: 200
//             ),
//             $this->getUrl(endpoint: 'customers/2064') => Http::response(
//                 body: $this->fixture('WooCommerce/CustomerDetail'),
//                 status: 200
//             ),
//         ]);

//         $api->orders()->getAndSync(549799);
//     }
// }
