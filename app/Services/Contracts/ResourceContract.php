<?php

namespace App\Services\Contracts;

interface ResourceContract
{
    /**
     * Retrieve the built Service from the Resource.
     *
     * @return ServiceContract
     */
    public function service(): ServiceContract;
}
