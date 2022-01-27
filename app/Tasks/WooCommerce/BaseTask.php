<?php

namespace App\Tasks\WooCommerce;

use App\Helpers\API\Testeable;
use App\Http\Clients\WooCommerce\WooCommerceClient;
use App\Models\Sync;


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

    protected WooCommerceClient $client;

    protected $id = 0;

    /**
     * The Constructor
     * 
     * Should receive the WooCommerceClient to make the requests
     * 
     * @param WooCommerceClient $client
     */
    public function __construct(WooCommerceClient $client) {
        $this->client = $client;
    }

    /**
     * [setId description]
     * @param int $id [description]
     */
    public function setId(int $id) {
        $this->id = $id;
    }

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
    public function sync(array $syncArgs = [], Sync|null $sync = null): void {
        $endpoint = $this->client->getEndpoint($this->name);

        if ($this->isTesting) {
            $endpoint->setTestingMode($this->isTesting);
            $endpoint->setTestingData($this->testingCollectionData[$this->name]);
            $endpoint->retrieveDataFromAPI($this->retrieveFromAPI);
        }
        
        $this->results = $endpoint->get($syncArgs, $sync, $this->id);

        // If id is greater than one, it means that we are trying to get a simple element
        if ($this->id > 0) {
            if ($this->results) {
                $this->handle($this->results);
            }
        }
        
        if ($this->results) {
            // Iterate the page result
            foreach ($this->results as $page => $results) {
    
                // Iterate the results in a page
                foreach ($results as $result) {
                    $this->handle($result);
                }
            }
        }
    }


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