<?php

namespace App\Data\Shared;

use App\Data\BaseData;

class MetaData extends BaseData {
    public static $id_field = 'id';
    
    public function __construct(
        public int|null $id,
        public string $key,
        public mixed $value
    ) {

    }
}