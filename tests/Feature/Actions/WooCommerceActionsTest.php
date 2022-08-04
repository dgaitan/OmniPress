<?php

namespace Tests\Feature\Actions;

use App\Actions\WooCommerce\Orders\UpdateOrderAction;
use App\Models\User;
use App\Models\WooCommerce\Order;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\Http\BaseHttp;

class WooCommerceActionsTest extends BaseHttp
{
    public function test_sync_should_returns_ok(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $data = $this->fixture('WooCommerce/OrderWithCustomer');

        $response = $this->post(route('kinja.api.v1.syncs.syncResource'), [
            'resource' => 'orders',
            'data' => $data
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Resource has been updated successfully!'
            ]);
    }

    public function test_it_should_create_an_order(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $data = $this->fixture('WooCommerce/OrderWithCustomer');

        $response = $this->post(route('kinja.api.v1.syncs.syncResource'), [
            'resource' => 'orders',
            'data' => $data
        ]);

        $order = Order::whereOrderId(549799)->first();

        $this->assertNotNull($order);
        $this->assertEquals('processing', $order->status);
    }

    public function test_it_should_return_error_if_resource_name_is_missed(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $data = $this->fixture('WooCommerce/OrderWithCustomer');

        $response = $this->post(route('kinja.api.v1.syncs.syncResource'), [
            'data' => $data
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => [
                    'resource' => [
                        'The resource field is required.'
                    ]
                ]
            ]);
    }
}
