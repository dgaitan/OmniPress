<?php

namespace App\Data\Customer;

use Spatie\LaravelData\Data;
use stdClass;

class MetaData extends Data {
    
    public function __construct(
        public int $id,
        public string $key,
        public mixed $value
    ) {

    }
}