<?php

namespace Tests\Feature\Services;

use App\Enums\ServiceType;
use App\Services\ServiceService;
use Tests\Feature\Models\BaseModelTest;

class ServiceServiceTest extends BaseModelTest
{
    public function test_service_creator(): void
    {
        $org = $this->create_org();
        $args = [
            'name' => 'kindhumans',
            'type' => ServiceType::WOOCOMMERCE,
            'creator_id' => $org->owner->id,
            'organization_id' => $org->id,
        ];
        $access = [
            'domain' => env('WOO_CUSTOMER_DOMAIN'),
            'customer_key' => env('WOO_CUSTOMER_KEY'),
            'customer_secret' => env('WOO_CUSTOMER_SECRET'),
        ];

        $service = (new ServiceService)->create($args, $access);

        $this->assertEquals('kindhumans', $service->name);
        $this->assertEquals('woocommerce', ServiceType::WOOCOMMERCE);
        $this->assertEquals('John', $service->creator->name);
        $this->assertEquals('Org 1', $service->organization->name);

        $this->assertSame(env('WOO_CUSTOMER_DOMAIN'), $service->access->domain);
        $this->assertSame(env('WOO_CUSTOMER_KEY'), $service->access->customer_key);
        $this->assertSame(env('WOO_CUSTOMER_SECRET'), $service->access->customer_secret);
    }
}
