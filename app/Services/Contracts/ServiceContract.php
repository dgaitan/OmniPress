<?php

namespace App\Services\Contracts;

interface ServiceContract {

    /**
     * A Service should implement a Make Request Method.
     *
     * @return void
     */
    public function makeRequest();

    /**
     * A sttic method to access to this service
     *
     * @return ServiceContract
     */
    public static function make(): ServiceContract;
}
