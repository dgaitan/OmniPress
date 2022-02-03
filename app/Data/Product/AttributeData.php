<?php

namespace App\Data\Product;

use App\Data\BaseData;

class AttributeData extends BaseData {

    public static $id_field = 'attribute_id';

    protected static $booleanFields = [
        'visible',
        'variation',
    ];
    
    public function __construct(
        public int $attribute_id,
        public string|null $name,
        public int|null $position,
        public ?bool $visible,
        public ?bool $variation,
        public ?array $options
    ) {
        $this->name = is_null($name) ? "" : $name;
        $this->position = is_null($position) ? 0 : $position;
        $this->visible = is_null($visible) ? false : $visible;
        $this->variation = is_null($variation) ? false : $variation;
    }
}