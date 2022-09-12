<?php

namespace Tests\Feature\Actions;

use App\Actions\GetDataFromCache;
use Illuminate\Support\Facades\Cache;

$testsGroup = 'actions';

it('should store a data in cache', function () {
    GetDataFromCache::run(
        tag: 'my_tag',
        cacheKey: 'my_cache_key',
        expiration: now()->addYear(),
        closure: function () {
            return 'My cached value';
        }
    );

    $this->assertEquals(
        'My cached value',
        Cache::tags('my_tag')->get('my_cache_key')
    );
})->group($testsGroup);

it('should store a data and return it', function () {
    $value = GetDataFromCache::run(
        tag: 'my_tag',
        cacheKey: 'my_cache_key',
        expiration: now()->addYear(),
        closure: function () {
            return 'My cached value';
        }
    );

    $this->assertEquals('My cached value', $value);
})->group($testsGroup);

it('should persist the data', function () {
    $value = GetDataFromCache::run(
        tag: 'my_tag',
        cacheKey: 'my_cache_key',
        expiration: now()->addYear(),
        closure: function () {
            return 'My cached value';
        }
    );

    $this->assertEquals('My cached value', $value);

    // Now let's call the action again and it should return the cached value.
    $value = GetDataFromCache::run(
        tag: 'my_tag',
        cacheKey: 'my_cache_key',
        expiration: now()->addYear(),
        closure: function () {
            return 'My cached value updated';
        }
    );

    expect($value)->not->toBe('My cached value updated');
    expect($value)->toBe('My cached value');
})->group($testsGroup);

it('should return the default value', function () {
    $value = GetDataFromCache::run(
        tag: 'my_tag',
        cacheKey: 'my_cache_key',
        expiration: now()->addYear(),
        closure: function () {
            return 'My cached value';
        }
    );

    expect($value)->toBe('My cached value');

    // We're going to clear the cache
    Cache::tags('my_tag')->flush();
    expect(Cache::tags('my_tag')->get('my_cache_key', 'default'))->toBe('default');

    // Now let's assign the value again.
    $value = GetDataFromCache::run(
        tag: 'my_tag',
        cacheKey: 'my_cache_key',
        expiration: now()->addYear(),
        closure: function () {
            return 'This is a new value';
        }
    );

    expect($value)->toBe('This is a new value');
    expect(Cache::tags('my_tag')->get('my_cache_key', 'default'))
        ->toBe('This is a new value');

})->group($testsGroup);
