<?php

namespace App\Console\Commands;

use App\Models\WooCommerce\Order;
use App\Services\QueryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
            query: Order::whereBetween('date_created', [now()->subDays(2), now()]),
            callback: function ($order) use ($ordersSynced) {
                $order->syncWithWoo();
                $ordersSynced++;
            }
        );

        Log::info(sprintf('Orders created between %s and %s were synced', now()->subDays(2)->format('F j, Y'), now()->format('F j, Y')));

        $this->info(sprintf('%s orders were synced', $ordersSynced));
    }
}
