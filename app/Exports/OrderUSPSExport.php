<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderUSPSExport implements FromArray, WithHeadings
{
    protected array $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Customer',
            'Order Status',
            'Order Date',
            'Shipping Amount',
            'Total'
        ];
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->orders;
    }
}
