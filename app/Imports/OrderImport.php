<?php

namespace App\Imports;

use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Product;
use App\Models\WooCommerce\OrderLine;
use App\Models\WooCommerce\Customer;
use App\Data\Http\OrderData;
use App\Data\Shared\AddressData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrderImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
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

        $data = OrderData::_fromCSV($row);
        $order = Order::firstOrNew(['order_id' => $row['id']]);
        $order->fill($data->toStoreData());

        // Maybe get the customer
        if ($customer = Customer::where('customer_id', $data->customer_id)->first()) {
            $order->customer_id = $customer->id;
        }

        $order->save();

        // Sync Order Lines
        if ($data->line_items) {
            
            foreach ($data->line_items as $item) {
                $orderLine = OrderLine::firstOrNew(['order_line_id' => $item->line_item_id]);
                $orderLine->fill($item->toStoreData());
                $product = Product::whereProductId($item->product_id)->first();

                if ($product) {
                    $orderLine->product_id = $product->id;
                }

                $orderLine->order_id = $order->id;
                $orderLine->save();
            }
        }
    }
}
