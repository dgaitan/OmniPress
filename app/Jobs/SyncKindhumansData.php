<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use Spatie\LaravelData\DataCollection;

class SyncKindhumansData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected DataCollection $data;

    protected $task;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DataCollection $data, $task)
    {
        $this->data = $data;
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            // Determine if the batch has been cancelled...

            return;
        }

        foreach ($this->data as $result) {
            (new $this->task)->handle($result);
        }
    }
}
