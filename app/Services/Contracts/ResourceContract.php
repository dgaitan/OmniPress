<?php

namespace App\Services\Contracts;

use App\Services\Contracts\ServiceContract;

interface ResourceContract
{
    /**
     * Retrieve the built Service from the Resource.
     *
     * @return ServiceContract
     */
    public function service(): ServiceContract;
}