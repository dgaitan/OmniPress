<?php

namespace App\Imports;

use App\Models\WooCommerce\Customer;
use App\Data\Shared\AddressData;
use App\Data\Shared\MetaData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;

    protected $fields = array(
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
        'meta_data'
    );

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row['email'])) return null;

        $customer = Customer::firstOrNew([
            'customer_id' => $row['id'],
            'email' => $row['email']
        ]);

        $data = [];
        foreach ( $this->fields as $field ) {
            $data[$field] = $row[$field];
        }
        
        $data['customer_id'] = $row['id'];

        if (!empty($row['billing'])) {
            $data['billing'] = AddressData::from(json_decode($row['billing'], true));
        }

        if (!empty($row['shipping'])) {
            $data['shipping'] = AddressData::from(json_decode($row['shipping'], true));
        }
        
        if (!empty($row['meta_data'])) {
            $data['meta_data'] = MetaData::collection(json_decode($row['meta_data'], true));
        }
        
        $data['is_paying_customer'] = '1' === $data['is_paying_customer'];

        $customer->fill($data);
        $customer->save();

        return $customer;
    }
}
