<?php

namespace App\Tasks\WooCommerce;

use App\Models\WooCommerce\Coupon;

class CouponTask extends BaseTask {
    
    /**
     * The task name accessor
     * 
     * @var string
     */
    protected string $name = 'coupons';

    /**
     * Main task after running initial tasks
     * 
     * @param mixed $data
     * @return void
     */
    public function handle($data): void {
        $coupon = Coupon::firstOrNew(['coupon_id' => $data->coupon_id]);
        $coupon->fill($data->toStoreData());
        $coupon->save();
    }
}