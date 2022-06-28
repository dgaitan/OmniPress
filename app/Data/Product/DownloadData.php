<?php

namespace App\Data\Product;

use App\Data\BaseData;

class DownloadData extends BaseData
{
    public static $id_field = 'download_id';

    public function __construct(
        public int $download_id,
        public string $name,
        public ?string $file
    ) {
    }
}
