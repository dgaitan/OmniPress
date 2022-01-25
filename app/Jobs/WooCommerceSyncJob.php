<?php

namespace App\Jobs;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Sync;
use App\Tasks\WooCommerceTask;

class WooCommerceSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 180;

    /**
     * Create a new job instance.
     *
     * @param $content_type - Content Type to retrieve. It canbe orders, products, etc..
     * @param $user - A simple representation of an user. ['id' => 1235, 'name' => 'jhon']
     * @param $description - Simple description
     * @return void
     */
    public function __construct(protected int $sync_id) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $sync = Sync::find($this->sync_id);
        $task = (new WooCommerceTask($sync))->dispatch();
    }
}
