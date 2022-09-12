<?php

namespace App\Actions;

use Closure;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class GetDataFromCache
{
    use AsAction;

    public function handle(
        string $tag,
        string $cacheKey,
        \DateTimeInterface|\DateInterval|int|null $expiration,
        Closure $closure,
        mixed $default = null
    ): mixed {
        if (Cache::tags($tag)->has($cacheKey)) {
            return Cache::tags($tag)->get($cacheKey, $default);
        }

        $data = $closure();

        Cache::tags($tag)->set($cacheKey, $data, $expiration);

        return $data;
    }
}
