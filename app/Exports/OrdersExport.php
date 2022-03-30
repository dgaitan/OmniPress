<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class OrdersExport implements FromArray, WithHeadings
{
    protected array $orders;

    public function __construct(array $orders) {
        $this->orders = $orders;
    }

    public function headings(): array
    {
        return [
            'Order #',
            'Date',
            'Status',
            'Last Name',
            'First Name',
            'Email',
            'Active Membership',
            // 'Active Subscription',
            'Billing Address',
            'Billing ZIP',
            'Billing State',
            'Billing City',
            'Billing Country',
            'Subtotal',
            'Coupon Code',
            'Total Discount',
            'Total Tax Collected',
            'Total Shipping Collected',
            'Total Order Amount',
            'Total Donated Amount',
            'SKU',
            'Product Name',
            'Product Price',
            'Quantity',
            'Product Tax',
            'SKU Sub Total',
            // 'Total Discount / sku: qty * discount',
            'GiveBack Type',
            'Donated Amount',
            'Bucket Cause',
            'Beneficiary Cause'
        ];
    }

    /**
    * @return array
    */
    public function array(): array
    {
        return $this->memberships;
    }
}
