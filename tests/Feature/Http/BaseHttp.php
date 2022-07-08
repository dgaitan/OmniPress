<?php

namespace Tests\Feature\Http;

use App\Services\WooCommerce\WooCommerceService;
use InvalidArgumentException;
use Tests\TestCase;

class BaseHttp extends TestCase
{
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
