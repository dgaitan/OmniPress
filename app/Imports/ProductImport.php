<?php

namespace App\Imports;

use App\Services\WooCommerce\Factories\ProductFactory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ProductImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;

    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $row['categories'] = unserialize($row['categories']);
        $row['tags'] = unserialize($row['tags']);
        $row['images'] = unserialize($row['images']);
        $row['attributes'] = unserialize($row['attributes']);
        $row['brands'] = unserialize($row['brands']);
        if (isset($row['meta_data'])) {
            $row['meta_data'] = unserialize($row['meta_data']);
        }

        $product = ProductFactory::make($row);
        $product = $product->sync();

        return $product;
    }
}
