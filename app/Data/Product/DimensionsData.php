<?php

namespace App\Data\Product;

use App\Data\BaseData;

class DimensionsData extends BaseData
{
    public function __construct(
        public ?float $lenght,
        public ?float $width,
        public ?float $height
    ) {
    }
}
