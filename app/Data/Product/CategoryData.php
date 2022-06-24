<?php

namespace App\Data\Product;

use App\Data\BaseData;

class CategoryData extends BaseData
{
    public static $id_field = 'category_id';

    public function __construct(
        public int $category_id,
        public string $name,
        public ?string $slug
    ) {
    }
}
