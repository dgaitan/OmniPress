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

class WooCommerceTask {

    use Testeable;

    protected WooCommerceClient $client;
    protected array $tasks = [
        'customers' => CustomerTask::class,
        'products' => ProductTask::class,
        'coupons' => CouponTask::class,
        'orders' => OrderTask::class
    ];

    public function __construct(
        protected Service $service
    ) {
        $this->client = new WooCommerceClient(
            Client::initialize($this->service)
        );
        $this->loadTasks();
    }

    protected function loadTasks() {
        foreach ($this->tasks as $task => $handler) {
            $this->tasks[$task] = new $handler($this->client);

            if ($this->isTesting) {
                $this->tasks[$task]->setTestingMode($this->isTesting)
                    ->setTestingCollectionData($this->testingCollectionData)
                    ->retrieveDataFromAPI($this->retrieveFromAPI);
            }
        }
    }

    public function syncCustomers(array $syncArgs = []) {
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