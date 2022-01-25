<?php

namespace App\Tasks\WooCommerce;

use App\Helpers\API\Testeable;
use App\Http\Clients\WooCommerce\WooCommerceClient;


abstract class BaseTask {

    // Is necessary this trait to test this class
    use Testeable;

    /**
     * Results retrieved from Http request on Client
     * 
     * @var array
     */
    protected array $results;
    
    /**
     * The task name accessor
     * 
     * @var string
     */
    protected string $name;

    /**
     * The Constructor
     * 
     * Should receive the WooCommerceClient to make the requests
     * 
     * @param WooCommerceClient $client
     */
    public function __construct() {}

    /**
     * Main task after running initial tasks
     * 
     * @param mixed $data
     * @return void
     */
    abstract public function handle($data): void;

    /**
     * Run the syncronization
     * 
     * @param array $syncArgs
     * @return void
     */
    // public function sync(Sync $sync, array $syncArgs = []): void {
    //     $endpoint = $this->client->getEndpoint($this->name);

    //     if ($this->isTesting) {
    //         $endpoint->setTestingMode($this->isTesting);
    //         $endpoint->setTestingData($this->testingCollectionData[$this->name]);
    //         $endpoint->retrieveDataFromAPI($this->retrieveFromAPI);
    //     }
        
    //     $this->results = $endpoint->get($syncArgs);
        
    //     if ($this->results) {
    //         if ($this->isTesting) {
    //             // Iterate the page result
    //             foreach ($this->results as $page => $results) {
        
    //                 // Iterate the results in a page
    //                 foreach ($results as $result) {
    //                     $this->handle($result);
    //                 }
        
    //                 sleep(2);
    //             }
    //         } else {
    //             $batch = Bus::batch([])->dispatch();

    //             foreach ( $this->results as $page => $results ) {
    //                 $batch->add(new SyncKindhumansData($results, $this));
    //             }

    //             $batch->then(function (Batch $batch) {
    //                 $sync->status = Sync::COMPLETED;
    //                 $sync->add_log(sprintf(
    //                     'Task completed with success at %s',
    //                     Carbon::now()->format('F j, Y @ H:i:s')
    //                 ));
    //                 $sync->save();
    //             });

    //             $batch->catch(function (Batch $batch, Throwable $e) {
    //                 $sync->status = Sync::FAILED;
    //                 $sync->add_log(sprintf(
    //                     'Task completed with success at %s',
    //                     Carbon::now()->format('F j, Y @ H:i:s')
    //                 ));
    //                 $sync->save();
    //             });
                
    //             $batch->dispatch();
    //             $sync->batch_id = $batch->id;
    //             $sync->save();
    //         }
    //     }
    // }

    public function syncCollection(
        string $collectionName,
        string $fieldId,
        $modelToAttach,
        string $model,
        \Spatie\LaravelData\DataCollection $collection,
        string $customFieldId = null,
        array $extraFields = []
    ): void {
        if ($collection) {
            $toAttach = [];

            foreach ($collection as $element) {
                $data = $element->toStoreData();
                
                $fieldIdName = is_null($customFieldId) ? $fieldId : $customFieldId;
                $modelElement = $model::firstOrNew([$fieldIdName => $data[$fieldId]]);
                
                if (!is_null($customFieldId)) {
                    $data[$customFieldId] = $data[$fieldId];
                }
                $data = [...$data, ...$extraFields];
                $modelElement->fill($data);
                $modelElement->save();
                
                $toAttach[] = $modelElement->id;
            }
            
            // If we can sync it automatically, let's do it
            if (method_exists($modelToAttach->{$collectionName}(), 'sync')) {
                $modelToAttach->{$collectionName}()->sync($toAttach);
            }
        }
    }
}