<?php

namespace Tests\Feature\Http\API;

use App\DTOs\PreOrderDTO;
use App\Models\PreSales\PreOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\Feature\Http\BaseHttp;

class PreOrderControllerTest extends BaseHttp {
    public function test_pre_orders_create_endpoint() {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $release = Carbon::now()->addDays(3);
        $response = $this->withToken($token)
            ->post('/api/v1/pre-orders/create', [
                'woo_order_id' => 1234,
                'customer_email' => 'test@mail.com',
                'customer_id' => 12345,
                'status' => 'pre-ordered',
                'release_date' => $release,
                'product_id' => 555,
                'product_name' => 'Test Product',
                'sub_total' => 3000,
                'taxes' => 0,
                'shipping' => 0,
                'total' => 3000,
                'released' => false
            ]);

        // $response->dd();
        $response->assertStatus(200);
    }

    public function test_it_should_not_create_because_is_missing_fields() {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $response = $this->withToken($token)
            ->post('/api/v1/pre-orders/create', [
                'customer_email' => 'test@mail.com',
                'customer_id' => 12345,
                'status' => 'pre-ordered',
                'product_id' => 555,
                'product_name' => 'Test Product',
                'sub_total' => 3000,
                'taxes' => 0,
                'shipping' => 0,
                'total' => 3000,
            ]);

        $response->assertStatus(400);
    }

    public function test_it_should_update_pre_order() {
        $release = Carbon::now()->addDays(3);
        $preOrderData = new PreOrderDTO([
            'woo_order_id' => 1234,
            'customer_email' => 'test@mail.com',
            'customer_id' => 12345,
            'status' => 'pre-ordered',
            'release_date' => $release,
            'product_id' => 555,
            'product_name' => 'Test Product',
            'sub_total' => 3000,
            'taxes' => 0,
            'shipping' => 0,
            'total' => 3000,
            'released' => false
        ]);
        $preOrder = $preOrderData->toModel(PreOrder::class);
        $preOrder->save();

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $response = $this->withToken($token)
            ->put(sprintf('/api/v1/pre-orders/%s/update', $preOrder->id), [
                'status' => 'completed'
            ]);

        $response->assertStatus(200);
        $preOrderUpdated = PreOrder::find($preOrder->id);
        $this->assertEquals($preOrderUpdated->status, 'completed');
    }
}
