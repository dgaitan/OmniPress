<?php

namespace Tests\Feature\Http;

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
            'http://host.docker.internal:10003/wp-json/wc/v3/%s',
            $endpoint
        );
    }
}
