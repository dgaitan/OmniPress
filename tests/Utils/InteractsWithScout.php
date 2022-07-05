<?php

namespace Tests\Utils;

use Laravel\Scout\EngineManager;

/**
 * Manages Laravel Scout in tests.
 *
 * This trait saved me about 40% test runtime on a relative
 * small test suite.
 *
 * Usage:
 *
 * Call `disableScout()` method inside TestCase::setUp() to disable
 * scout using the NullEngine. All search will results empty but you'll
 * save time when working with Searchable models.
 *
 * When you need to test searches, call `withScout()` at the beginning of
 * your test, this will re-enable the old Scout Engine.
 *
 * `clearSearchIndexes()` is a TNTSearchEngine-specific function, it removes
 * all file in `storage/app/search` that ends with `.index` and begins with
 * the given scout prefix. This ensure consistency between runs.
 * Remember to set your SCOUT_PREFIX env variable in phpunit.xml.
 * If you are not using TNTSearch or does not want to clear the indexes every
 * test, remove the call to this method inside `withScout()`.
 *
 * Inspired by:
 * https://adamwathan.me/2016/01/21/disabling-exception-handling-in-acceptance-tests/
 *
 * @author Leonardo Nodari <nodarileonardo@gmail.com>
 *
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */
trait InteractsWithScout
{
    protected $oldEngineManager;

    /**
     * Remove all test indexes before running a test
     *
     * @return $this
     */
    protected function clearSearchIndexes()
    {
        $indexes = collect(\Storage::allFiles('search'))
            ->filter(function ($filename) {
                return starts_with(basename($filename), config('scout.prefix')) && ends_with(basename($filename), '.index');
            })
            ->toArray();

        if (! empty($indexes)) {
            \Storage::delete($indexes);
        }

        return $this;
    }

    /**
     * Disable Laravel Scout Engine Manager
     *
     * @return $this
     */
    protected function disableScout()
    {
        $this->oldEngineManager = $this->app->make(EngineManager::class);

        $this->app->instance(EngineManager::class, new class($this->app) extends EngineManager
        {
            /**
             * Always return a null engine
             *
             * @param  null|string  $name
             * @return \Laravel\Scout\Engines\Engine
             */
            public function engine($name = null)
            {
                return $this->createNullDriver();
            }
        });

        return $this;
    }

    /**
     * Re-enable Laravel Scout Engine Manager
     *
     * @return $this
     */
    protected function withScout()
    {
        $this->clearSearchIndexes();
        $this->app->instance(EngineManager::class, $this->oldEngineManager);

        return $this;
    }
}
