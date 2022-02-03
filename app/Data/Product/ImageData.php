<?php

namespace App\Data\Product;

use App\Data\BaseData;

class ImageData extends BaseData {

    public static $id_field = 'image_id';
    
    public function __construct(
        public int $image_id,
        public ?string $date_created,
        public ?string $date_modified,
        public ?string $src,
        public string|null $name,
        public string|null $alt
    ) {
        $this->name = is_null($name) ? "" : $name;
        $this->alt = is_null($alt) ? "" : $alt;
    }
}