<?php

namespace App\Imports;

use App\Services\WooCommerce\Factories\OrderFactory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class OrderImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;

    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $row['meta_data'] = json_decode($row['meta_data'], true);
        $row['line_items'] = json_decode($row['line_items'], true);
        $row['tax_lines'] = json_decode($row['tax_lines'], true);
        $row['fee_lines'] = json_decode($row['fee_lines'], true);
        $row['shipping_lines'] = json_decode($row['shipping_lines'], true);
        $row['coupon_lines'] = json_decode($row['coupon_lines'], true);
        $row['refunds'] = json_decode($row['refunds'], true);
        $row['billing'] = json_decode($row['billing'], true);
        $row['shipping'] = json_decode($row['shipping'], true);

        $order = OrderFactory::make($row);
        $order = $order->sync();

        return $order;
    }
}
