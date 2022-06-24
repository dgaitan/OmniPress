<?php

namespace App\Exports\Products;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductSubscriptionExport implements FromArray, WithHeadings
{
    protected array $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function headings(): array
    {
        return [
            'Product ID',
            'Parent Product ID',
            'Name',
            'SKU',
            'Type',
            'Price',
            'Fee',
            'Intervals',
        ];
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->products;
    }
}
