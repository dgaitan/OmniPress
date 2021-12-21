<?php

namespace App\Data\Shared;

use App\Data\BaseData;

class MetaData extends BaseData {
    public static $id_field = 'meta_id';
    
    public function __construct(
        public int $meta_id,
        public string $key,
        public mixed $value
    ) {

    }
}