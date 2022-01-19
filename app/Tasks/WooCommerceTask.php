<?php

namespace App\Tasks;

use App\Models\Service;
use App\Http\Clients\Client;
use App\Http\Clients\WooCommerce\WooCommerceClient;
use App\Tasks\WooCommerce\CustomerTask;
use App\Tasks\WooCommerce\CouponTask;
use App\Tasks\WooCommerce\OrderTask;
use App\Tasks\WooCommerce\ProductTask;
use App\Helpers\API\Testeable;
use Illuminate\Support\Facades\Log;


class WooCommerceTask {

    use Testeable;

    /**
     * WooCommerce CLient Instance
     * 
     * @var WooCommerceClient
     */
    protected WooCommerceClient $client;

    /**
     * Tasks registered to this task manager
     * 
     * @var array
     */
    protected array $tasks = [
        'customers' => CustomerTask::class,
        'products' => ProductTask::class,
        'coupons' => CouponTask::class,
        'orders' => OrderTask::class
    ];

    /**
     * Task Constructor
     * 
     * Is necessary a service to can run tasks because we to attach the data
     * retrieved from sync to a service.
     * 
     * @param Service
     */
    public function __construct() {
        $this->client = new WooCommerceClient(
            new Client
        );
        
        $this->loadTasks();
    }

    /**
     * Load the tasks registered
     * 
     * @return void
     */
    protected function loadTasks() : void {
        foreach ($this->tasks as $task => $handler) {
            $this->tasks[$task] = new $handler($this->client);

            if ($this->isTesting) {
                $this->tasks[$task]->setTestingMode($this->isTesting)
                    ->setTestingCollectionData($this->testingCollectionData)
                    ->retrieveDataFromAPI($this->retrieveFromAPI);
            }
        }
    }

    /**
     * Syncronize Customers
     * 
     * @param  array
     * @return void
     */
    public function syncCustomers(array $syncArgs = []): void {
        Log::info('Syncing Customers');
        $this->sync('customers', $syncArgs);
    }

    public function syncCoupons(array $syncArgs = []) {
        $this->sync('coupons', $syncArgs);
    }

    public function syncOrders(array $syncArgs = []) {
        $this->sync('orders', $syncArgs);
    }

    public function syncProducts(array $syncArgs = []) {
        $this->sync('products', $syncArgs);
    }

    public function _sync(string $type, array $syncArgs = []) {
        $this->sync($type, $syncArgs);
    }

    protected function sync(string $task, array $syncArgs = []): void {
        $task = $this->tasks[$task];
        
        if ($this->isTesting) {
            $task->setTestingMode($this->isTesting)
            ->setTestingCollectionData($this->testingCollectionData)
            ->retrieveDataFromAPI($this->retrieveFromAPI);
        }

        $task->sync($syncArgs);
    }
}