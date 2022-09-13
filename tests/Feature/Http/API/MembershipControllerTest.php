<?php

namespace Tests\Feature\Http\API;

use App\Jobs\Memberships\NewMembershipJob;
use App\Jobs\Memberships\SyncNewMemberOrder;
use App\Mail\Memberships\MembershipRenewed;
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

$testsGroup = 'memberships_api';

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

it('should create a new membership when call new membership endpoint', function () {
    Queue::fake([
        NewMembershipJob::class,
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
            'shipping_status' => 'pending',
        ],
        'kind_cash' => [
            'points' => 750,
            'last_earned' => 750,
        ],
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

    // Assert Product
    $this->assertEquals($membershipProduct->product_id, $membership->product_id);
    $this->assertEquals($membershipProduct->product_id, $membership->product->product_id);

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
})->group($testsGroup);

it('should returns a json with the membership detail when calling show endpoint', function () {
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

    $response = $this->get('api/v1/memberships/1');

    $m = Membership::find(1);

    $response->assertOk();
    $response->assertStatus(200);
    $response->assertJson([
        'id' => 1,
        'customer_email' => 'ram@ram.com',
        'product_id' => 160768,
        'price' => 3500,
        'price_as_money' => [
            'amount' => '3500',
            'currency' => 'USD',
            'formatted' => '$35.00',
        ],
        'shipping_status' => 'pending',
        'status' => 'active',
        'last_payment_intent' => Carbon::parse($m->last_payment_intent)->toJson(),
        'gift_product' => [
            'id' => 544443,
            'name' => 'Kindhumans Youth Be Nice Membership',
            'sku' => 'KH00841',
        ],
        'payment_intents' => 0,
        'user_picked_gift' => true,
        'customer' => [
            'id' => 1,
            'email' => 'ram@ram.com',
            'customer_id' => 2064,
        ],
        'cash' => [
            'points' => 750,
            'last_earned' => 750,
        ],
        'is_active' => true,
        'is_in_renewal' => false,
        'is_awaiting_pick_gift' => false,
        'is_expired' => false,
        'is_cancelled' => false,
    ]);
})->group($testsGroup);

it('should return 404 not found when request an invalid membership id', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $response = $this->get('api/v1/memberships/1111');

    $response->assertStatus(404);
    $response->assertJson([
        'message' => 'Membership not found',
    ]);
})->group($testsGroup);

it('should be able to add kind cash', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $this->requestNewMembership();

    $request = $this->post('/api/v1/memberships/1/cash/add', [
        'points' => 100,
        'message' => 'Points earned by purchase',
    ]);

    $request->assertOk();
    $request->assertStatus(200);

    $request->assertJson([
        'id' => 1,
        'points' => 850,
        'last_earned' => 100,
    ]);
})->group($testsGroup);

it('should renew a membership from kindhumans store', function () {
    $this->requestNewMembership();
    Mail::fake();
    Queue::fake();

    // Let's mock the order that was created from kindhumans when customer
    // renew it manually
    Http::fake([
        $this->getUrl(endpoint: 'orders/454545') => Http::response(
            body: $this->fixture('Memberships/OrderFromKindhumans'),
            status: 200
        ),
        $this->getUrl(endpoint: 'customers/2064') => Http::response(
            body: $this->fixture('WooCommerce/CustomerDetail'),
            status: 200
        ),
    ]);

    $api = WooCommerceService::make();
    $api->orders()->getAndSync(454545);

    // Let's mock a membership In-Renewal
    $membership = Membership::find(1);
    $membership->update([
        'end_date' => Carbon::now()->subDays(20),
        'status' => Membership::IN_RENEWAL_STATUS,
    ]);

    $response = $this->post('api/v1/memberships/renew', [
        'order_id' => 454545,
        'membership_id' => 1,
        'gift_product_id' => 544443
    ]);

    $response->assertOk();
    $response->assertStatus(200);

    Mail::assertQueued(MembershipRenewed::class);
    Queue::assertPushed(SyncNewMemberOrder::class);
})->group($testsGroup);
