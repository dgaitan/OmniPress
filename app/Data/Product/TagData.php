<?php

namespace App\Data\Product;

use App\Data\BaseData;

class TagData extends BaseData {

    public static $id_field = 'tag_id';
    
    public function __construct(
        public int $tag_id,
        public string $name,
        public ?string $slug
    ) {

    }
}