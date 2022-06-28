<?php

namespace App\Console\Commands\Printforia;

use App\Jobs\Printforia\CheckPrintforiaOrders as PrintforiaCheckPrintforiaOrders;
use Illuminate\Console\Command;

class CheckPrintforiaOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'printforia:check-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        PrintforiaCheckPrintforiaOrders::dispatch();
    }
}
