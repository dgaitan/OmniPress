<?php

namespace App\Console\Commands;

use App\Models\WooCommerce\Order;
use App\Services\QueryService;
use Illuminate\Console\Command;

class SyncOrdersCreatedToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kinja:sync-orders-created-today';

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
        $ordersSynced = 0;
        QueryService::walkTrough(
            query: Order::whereBetween('date_created', [now()->subMonth(), now()]),
            callback: function ($order) use ($ordersSynced) {
                $order->syncWithWoo();
                $ordersSynced++;
            }
        );

        $this->info(sprintf('%s orders were synced', $ordersSynced));
    }
}
