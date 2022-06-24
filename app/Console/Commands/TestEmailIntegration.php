<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestEmailIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:test-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Illuminate\Support\Facades\Mail::to('dgaitan@kindhumans.com')
            ->send(new \App\Mail\TestMail);
    }
}
