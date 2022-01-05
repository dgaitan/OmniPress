<?php

namespace App\Tasks\WooCommerce;

use App\Helpers\API\Testeable;
use App\Http\Clients\WooCommerce\WooCommerceClient;
use App\Models\Service;


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
     * The service where this sync is loading
     * 
     * @var Service
     */
    protected Service $service;

    /**
     * The Constructor
     * 
     * Should receive the WooCommerceClient to make the requests
     * 
     * @param WooCommerceClient $client
     */
    public function __construct(protected WooCommerceClient $client) {}

    /**
     * Main task after running initial tasks
     * 
     * @param mixed $data
     * @return void
     */
    abstract protected function handle($data): void;

    /**
     * @param Service $service - The service instance to set
     * @return static
     */
    public function setService(Service $service): static {
        $this->service = $service;

        return $this;
    }

    /**
     * Run the syncronization
     * 
     * @param array $syncArgs
     * @return void
     */
    public function sync(array $syncArgs = []): void {
        $endpoint = $this->client->getEndpoint($this->name);

        if ($this->isTesting) {
            $endpoint->setTestingMode($this->isTesting);
            $endpoint->setTestingData($this->testingCollectionData[$this->name]);
            $endpoint->retrieveDataFromAPI($this->retrieveFromAPI);
        }
        
        $this->results = $endpoint->get(...$syncArgs);
        
        if ($this->results) {
            // Iterate the page result
            foreach ($this->results as $page => $results) {
    
                // Iterate the results in a page
                foreach ($results as $result) {
                    $this->handle($result);
                }
    
                sleep(2);
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