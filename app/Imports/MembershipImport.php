<?php

namespace App\Imports;

use App\Services\WooCommerce\Factories\MembershipFactory;
use App\Models\KindCash;
use App\Models\Membership;
use App\Models\WooCommerce\Customer;
use App\Models\WooCommerce\Product;
use App\Models\WooCommerce\Order;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class MembershipImport implements ToModel, WithProgressBar, WithHeadingRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row['order_ids'] = json_decode($row['order_ids'], true);
        $row['kind_cash'] = json_decode($row['kind_cash']);

        $membership = MembershipFactory::make($row);
        $membership = $membership->sync();

        return $membership;
    }
}
