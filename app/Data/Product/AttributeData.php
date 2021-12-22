<?php

namespace App\Data\Product;

use App\Data\BaseData;

class AttributeData extends BaseData {

    public static $id_field = 'attribute_id';
    
    public function __construct(
        public int $attribute_id,
        public string $name,
        public int $position,
        public bool $visible,
        public bool $variation,
        public ?array $options
    ) {

    }
}