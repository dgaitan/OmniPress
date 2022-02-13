<?php

namespace App\Services\Contracts;

interface ServiceContract {

    /**
     * A Service should implement a Make Request Method.
     *
     * @return void
     */
    public function makeRequest();
}