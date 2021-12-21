<?php

namespace App\Helpers\API;

trait Testeable {

    /**
     * Is this class in testing mode?
     * 
     * @var bool
     */
    protected $isTesting = false;
    
    /**
     * Testing data.
     * 
     * should have the next format:
     * [
     *  'key' => array(...value),
     *  'key2' => array(...value)
     * ]
     * 
     * @var array
     */
    protected $testingCollectionData = [];

    /**
     * Testing a single data
     * 
     * @var array
     */
    protected $testingData = [];

    /**
     * Should use test data or retrieve from api?
     * 
     * It means that we going to make the api request over use the testing data
     * 
     * @var bool
     */
    protected $retrieveFromAPI = false;

    /**
     * Set testing mode
     * 
     * @param bool $isTesting
     * @return self
     */
    public function setTestingMode(bool $isTesting): self {
        $this->isTesting = $isTesting;
        return $this;
    }

    /**
     * Set a collection of data
     * 
     * it should follows the next format:
     * 
     * [
     *  'customers' => ...,
     *  'coupons' => ...,
     * ]
     * 
     * @param array $data
     * @return self
     */
    public function setTestingCollectionData(array $data): self {
        $this->testingCollectionData = $data;
        return $this;
    }

    /**
     * Set testing data
     * 
     * @param array $data
     * @return self
     */
    public function setTestingData(array $data): self {
        $this->testingData = $data;
        return $this;
    }

    /**
     * Set if we should use the api over the testing data
     * 
     * @param bool $retrieveFromApi
     * @return self
     */
    public function retrieveDataFromAPI(bool $retrieveFromAPI = false): self {
        $this->retrieveFromAPI = $retrieveFromAPI;
        return $this;
    }

}