<?php

namespace Tests\Feature\Services\WooCommerce;

use App\Actions\Donations\AssignOrderDonationAction;
use App\Actions\Donations\AssignOrderDonationToCustomerAction;
use App\Actions\WooCommerce\Orders\SyncOrderLineItemProductsAction;
use App\Jobs\Donations\AssignOrderDonationJob;
use App\Jobs\WooCommerce\Orders\SyncOrderLineItemProductsJob;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Order as WooCommerceOrder;
use App\Models\WooCommerce\PaymentMethod;
use App\Notifications\Orders\NewOrderNotification;
use App\Services\WooCommerce\DataObjects\Order;
use App\Services\WooCommerce\Resources\OrderResource;
use App\Services\WooCommerce\WooCommerceService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\Feature\Http\BaseHttp;

class WooCommerceOrderResourceTest extends BaseHttp
{
    use RefreshDatabase;

    public function test_order_resource_instance()
    {
        $api = WooCommerceService::make();

        $this->assertInstanceOf(OrderResource::class, $api->orders());
        $this->assertClassHasAttribute('endpoint', OrderResource::class);
        $this->assertClassHasAttribute('factory', OrderResource::class);

        $this->assertInstanceOf(WooCommerceService::class, $api->orders()->service());
    }

    public function test_getting_order_using_order_resource()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'orders/727') => Http::response(
                body: $this->fixture('WooCommerce/OrderDetail'),
                status: 200
            ),
        ]);

        $order = $api->orders()->get(727);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(727, $order->id);
        $this->assertEquals('processing', $order->status);
        $this->assertEquals(2, count($order->line_items));
    }

    public function test_exception_when_retrieve_an_order_detail()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('orders/727') => Http::response(
                body: $this->fixture('WooCommerce/OrderDetail'),
                status: 408
            ),
        ]);

        $order = $api->orders()->get(727);

        $this->assertTrue(is_null($order));
    }

    public function test_gettings_and_storing_data_in_db()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('orders/727') => Http::response(
                body: $this->fixture('WooCommerce/OrderDetail'),
                status: 200
            ),
        ]);

        $order = $api->orders()->getAndSync(727);

        $this->assertInstanceOf(WooCommerceOrder::class, $order);
        $this->assertEquals(727, $order->order_id);
        $this->assertInstanceOf(Carbon::class, $order->date_created);
        $this->assertEquals(2, $order->items->count());
    }

    public function test_getting_order_list()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('orders*') => Http::response(
                body: $this->fixture('WooCommerce/OrderList'),
                status: 200
            ),
        ]);

        $orders = $api->orders()->collect();

        $this->assertInstanceOf(Collection::class, $orders);
        $this->assertEquals(2, $orders->count());
        $this->assertInstanceOf(Order::class, $orders->first());
    }

    public function test_errors_when_get_list_of_orders()
    {
        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('orders*') => Http::response(
                body: $this->fixture('WooCommerce/OrderList'),
                status: 408
            ),
        ]);

        $orders = $api->orders()->collect();

        $this->assertTrue(is_null($orders));
    }

    public function test_creating_an_order_with_a_guest_user(): void
    {
        $paymentMethod = PaymentMethod::create([
            'payment_method_id' => 'kindhumans_stripe_gateway',
            'title' => 'Credit Card',
            'order' => 1,
            'enabled' => true,
            'method_title' => 'Kindhumans Payment Gateway',
        ]);

        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl('orders/418136') => Http::response(
                body: $this->fixture('WooCommerce/OrderDetailWithGuestCustomer'),
                status: 200
            ),
        ]);

        $order = $api->orders()->getAndSync(418136);

        $this->assertInstanceOf(WooCommerceOrder::class, $order);
        $this->assertEquals('completed', $order->status);
        $this->assertInstanceOf(PaymentMethod::class, $order->paymentMethod);
        $this->assertEquals(
            $paymentMethod->payment_method_id,
            $order->paymentMethod->payment_method_id
        );
        $this->assertEquals($paymentMethod->id, $order->paymentMethod->id);
        $this->assertTrue(is_null($order->customer));
    }

    public function test_creating_an_order_with_customer(): void
    {
        $paymentMethod = PaymentMethod::create([
            'payment_method_id' => 'kindhumans_stripe_gateway',
            'title' => 'Credit Card',
            'order' => 1,
            'enabled' => true,
            'method_title' => 'Kindhumans Payment Gateway',
        ]);

        $customer = Customer::create([
            'customer_id' => 2064,
            'email' => 'smlueker@yahoo.com',
            'first_name' => 'John',
            'last_name' => 'Bar',
            'role' => 'customer',
            'username' => 'smlueker',
            'billing' => '{}',
            'shipping' => '{}',
            'is_paying_customer' => false
        ]);

        $api = WooCommerceService::make();

        Http::fake([
            $this->getUrl(endpoint: 'orders/549799') => Http::response(
                body: $this->fixture('WooCommerce/OrderWithCustomer'),
                status: 200
            ),
            $this->getUrl(endpoint: 'customers/2064') => Http::response(
                body: $this->fixture('WooCommerce/CustomerDetail'),
                status: 200
            )
        ]);

        Notification::fake();
        Bus::fake();

        $order = $api->orders()->getAndSync(549799);

        // We notify to admins that new order were created.
        Notification::assertSentTo($order, NewOrderNotification::class);

        $this->assertInstanceOf(WooCommerceOrder::class, $order);
        $this->assertEquals('processing', $order->status);
        $this->assertInstanceOf(PaymentMethod::class, $order->paymentMethod);
        $this->assertEquals(
            $paymentMethod->payment_method_id,
            $order->paymentMethod->payment_method_id
        );

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->email, $order->customer->email);
        $this->assertEquals($customer->customer_id, $order->customer->customer_id);
    }

    public function test_syncing_order_items_after_create_an_order()
    {
        Http::fake([
            $this->getUrl(endpoint: 'orders/549799') => Http::response(
                body: $this->fixture('WooCommerce/OrderWithCustomer'),
                status: 200
            ),
            $this->getUrl(endpoint: 'customers/2064') => Http::response(
                body: $this->fixture('WooCommerce/CustomerDetail'),
                status: 200
            ),
            $this->getUrl(endpoint: 'payment_gateways/kindhumans_stripe_gateway') => Http::response(
                body: $this->fixture('WooCommerce/PaymentMethodDetail'),
                status: 200
            )
        ]);

        $api = WooCommerceService::make();

        Notification::fake();
        Bus::fake([
            SyncOrderLineItemProductsJob::class
        ]);

        $api->paymentMethods()->getAndSync('kindhumans_stripe_gateway');
        $api->customers()->getAndSync(2064);
        $order = $api->orders()->getAndSync(549799);

        Bus::assertDispatched(SyncOrderLineItemProductsJob::class);
        
        $this->assertEquals(2, $order->items->count());
    }
}
