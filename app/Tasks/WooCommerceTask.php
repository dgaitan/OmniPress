<?php

namespace App\Tasks;

use Throwable;
use App\Models\Sync;
use App\Http\Clients\Client;
use App\Http\Clients\WooCommerce\WooCommerceClient;
use App\Jobs\SyncKindhumansData;
use App\Tasks\WooCommerce\CustomerTask;
use App\Tasks\WooCommerce\CouponTask;
use App\Tasks\WooCommerce\OrderTask;
use App\Tasks\WooCommerce\ProductTask;
use App\Helpers\API\Testeable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use Carbon\Carbon;


class WooCommerceTask {

    use Testeable;

    /**
     * WooCommerce CLient Instance
     * 
     * @var WooCommerceClient
     */
    protected WooCommerceClient $client;

    protected Sync|null $sync = null;

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
     * @param Sync
     */
    public function __construct(Sync|null $sync = null) {
        $this->sync = $sync;
        $this->client = new WooCommerceClient(
            new Client
        );
        
        // $this->loadTasks();
    }

    /**
     * Return the available tasks
     * 
     * @return array
     */
    public function getAvailableTasks(): array {
        return array_keys($this->tasks);
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

    public function dispatch(array $syncArgs = []) {
        $this->sync(strtolower($this->sync->content_type), $syncArgs);
    }

    protected function sync(string $task, array $syncArgs = []): void {
        if (!$this->sync) return;

        $endpoint = $this->client->getEndpoint($task);
        $results = $endpoint->get($syncArgs);
        $sync = $this->sync;

        if ($this->isTesting) {
            $endpoint->setTestingMode($this->isTesting);
            $endpoint->setTestingData($this->testingCollectionData[$task]);
            $endpoint->retrieveDataFromAPI($this->retrieveFromAPI);
        }
        
        if ($results) {
            if ($this->isTesting) {
                // Iterate the page result
                foreach ($results as $page => $results) {
        
                    // Iterate the results in a page
                    foreach ($results as $result) {
                        (new $this->tasks[$task])->handle($result);
                    }
                }
            } else {
                $batches = [];
                foreach ( $results as $page => $results ) {
                    $batches[] = new SyncKindhumansData($results, $this->tasks[$task]);
                }

                $batch = Bus::batch($batches)
                    ->then(function (Batch $batch) use ($sync) {
                        $sync->status = Sync::COMPLETED;
                        $sync->add_log(sprintf(
                            'Task completed with success at %s',
                            Carbon::now()->format('F j, Y @ H:i:s')
                        ));
                        $sync->save();

                    })->catch(function (Batch $batch, Throwable $e) use ($sync) {
                        $sync->status = Sync::FAILED;
                        $sync->add_log(sprintf(
                            'Task failed with error %s at %s',
                            $e->getMessage(),
                            Carbon::now()->format('F j, Y @ H:i:s')
                        ));
                        $sync->save();
                        
                    })->dispatch();
                
                $sync->batch_id = $batch->id;
                $sync->save();
            }
        }
    }
}