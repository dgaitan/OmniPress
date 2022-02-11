<?php

namespace App\Data\Http;

use App\Data\BaseData;

class MembershipData extends BaseData {
    public static $id_field = 'membership_id';
    
    public function __construct(
        public int $membership_id,
        public int $customer_id,
        public string $customer_email,
        public string $start_at,
        public string $end_at,
        public int $price,
        public string $status,
        public ?string $order_ids,
        public string $shipping_status,
        public ?string $product_id,
        public int $gift_product_id,
        public ?string $pending_order_id,
        public ?string $last_payment_intent,
        public int $payment_intents,
        public array $kind_cash
    ) {

    }
}