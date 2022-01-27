<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Tasks\WooCommerceTask;

class SingleWooCommerceSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id = 0;
    protected $content_type = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $element_id, string $content_type)
    {
        $this->id = $element_id;
        $this->content_type = $content_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $task = new WooCommerceTask();
        $task->setId($this->element_id);
        $task->_sync($this->content_type);
    }
}
