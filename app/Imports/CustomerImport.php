<?php

namespace App\Imports;

use App\Services\WooCommerce\Factories\CustomerFactory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class CustomerImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;

    protected $fields = [
        'email',
        'date_created',
        'date_modified',
        'first_name',
        'last_name',
        'role',
        'username',
        'is_paying_customer',
        'orders_count',
        'total_spent',
        'avatar_url',
        'billing',
        'shipping',
        'meta_data',
    ];

    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (empty($row['email'])) {
            return null;
        }

        // prepare format data
        $row['billing'] = json_decode($row['billing'], true);
        $row['shipping'] = json_decode($row['shipping'], true);
        $row['meta_data'] = json_decode($row['meta_data'], true);
        $row['is_paying_customer'] = '1' === $row['is_paying_customer'];

        $customer = CustomerFactory::make($row);
        $customer = $customer->sync();

        return $customer;
    }
}
