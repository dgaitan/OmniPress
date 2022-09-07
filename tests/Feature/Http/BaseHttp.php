<?php

namespace Tests\Feature\Http;

use App\Services\WooCommerce\WooCommerceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;
use Tests\Utils\InteractsWithScout;
use Tests\Utils\InteractsWithStripe;

class BaseHttp extends TestCase
{
    use InteractsWithScout;
    use InteractsWithStripe;
    use RefreshDatabase;

    public function fixture(string $name): array
    {
        $file = file_get_contents(
            filename: base_path("tests/Fixtures/$name.json"),
        );

        if (! $file) {
            throw new InvalidArgumentException(
                message: "Cannot find fixture: [$name] at tests/Fixtures/$name.json",
            );
        }

        return json_decode(
            json: $file,
            associative: true,
        );
    }

    public function getUrl(string $endpoint): string
    {
        return sprintf(
            '%s/wp-json/wc/v3/%s',
            env('WOO_CUSTOMER_DOMAIN'),
            $endpoint
        );
    }

    protected function get_woocommerce_service(): WooCommerceService
    {
        return WooCommerceService::make();
    }
}
