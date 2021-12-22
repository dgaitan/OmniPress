<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Customer;
use App\Data\Http\CustomerData;
use App\Http\Clients\WooCommerce\WooCommerceClient;
use App\Helpers\API\Testeable;

class CustomerTask {

    use Testeable;

    protected array $results;

    public function __construct(protected WooCommerceClient $client) {}

    protected function handle(CustomerData $data): bool {
        $customer = Customer::firstOrNew(['customer_id' => $data->customer_id]);
        $customer->fill($data->toStoreData());
        $customer->save();
        
        return true;
    }
    
    public function sync(array $syncArgs = []): void {
        $endpoint = $this->client->getEndpoint('customers');

        if ($this->isTesting) {
            $endpoint->setTestingMode($this->isTesting);
            $endpoint->setTestingData($this->testingCollectionData['customers']);
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
}