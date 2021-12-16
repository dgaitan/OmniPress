<?php

namespace App\Data\Coupon;

use App\Data\BaseData;

class CouponSettingData extends BaseData {
    public function __construct(
        public ?string $usage_count,
        public ?array $product_ids,
        public ?array $excluded_product_ids,
        public ?int $usage_limit,
        public ?int $usage_limit_per_user,
        public ?int $usage_limit_to_x_items,
        public ?bool $free_shipping,
        public ?array $product_categories,
        public ?array $excluded_product_categories,
        public ?bool $exclude_sale_items,
        public ?int $minimum_amount,
        public ?int $maximum_amount,
        public ?array $email_restrictions,
        public ?array $used_by
    ) {

    }

    public static function _fromResponse(array $data = []): static {
        $_data = [];
        $attributes = static::getAttributes();

        if ($data) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $attributes)) {
                    continue;
                }

                $_data[$key] = $value;
            }
        }

        return static::from($_data);
    }
}