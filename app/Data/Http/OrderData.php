<?php

namespace App\Data\Http;

use Spatie\LaravelData\DataCollection;
use App\Data\BaseData;
use App\Data\Shared\AddressData;

class OrderData extends BaseData {
    public static $id_field = 'order_id';

    protected static $priceFields = [
        'discount_total',
        'discount_tax',
        'shipping_total',
        'shipping_tax',
        'cart_tax',
        'total_tax',
        'total'
    ];

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class,
        'line_items' => \App\Data\Order\LineItemData::class,
        'tax_lines' => \App\Data\Order\TaxLineData::class,
        'shipping_lines' => \App\Data\Order\ShippingLineData::class,
        'fee_lines' => \App\Data\Order\FeeLineData::class,
        'coupon_lines' => \App\Data\Order\CouponLineData::class,
        'refunds' => \App\Data\Order\RefundData::class
    ];

    protected static $objectFields = ['billing', 'shipping'];

    protected static $booleanFields = [
        'set_paid'
    ];

    /**
     * Keep Price Value
     * 
     * Change it if you will import data from a csv
     * 
     * @var boolean
     */
    protected static $keepPriceValue = false;
    
    public function __construct(
        public int $order_id,
        public ?int $parent_id,
        public int $number,
        public string $order_key,
        public ?string $created_via,
        public string $version,
        public string $status,
        public string $currency,
        public string $date_created,
        public ?string $date_modified = null,
        public int $discount_total,
        public int $discount_tax,
        public int $shipping_total,
        public int $shipping_tax,
        public int $cart_tax,
        public int $total,
        public int $total_tax,
        public ?bool $prices_include_tax,
        public int $customer_id,
        public string $customer_ip_address,
        public string $customer_user_agent,
        public ?string $customer_note,
        public AddressData $billing,
        public AddressData $shipping,
        public ?string $payment_method = null,
        public ?string $transaction_id = '',
        public ?string $date_paid = '',
        public ?string $date_completed = null,
        public ?string $cart_hash = '',
        public ?bool $set_paid,
        /** @var \App\Data\Shared\MetaData[] */
        public ?DataCollection $meta_data,
        /** @var \App\Data\Order\LineItemData[] */
        public ?DataCollection $line_items,
        /** @var \App\Data\Order\TaxLineData[] */
        public ?DataCollection $tax_lines,
        /** @var \App\Data\Order\ShippingLineData[] */
        public ?DataCollection $shipping_lines,
        /** @var \App\Data\Order\FeeLineData[] */
        public ?DataCollection $fee_lines,
        /** @var \App\Data\Order\CouponLineData[] */
        public ?DataCollection $coupon_lines,
        /** @var \App\Data\Order\RefundData[] */
        public ?DataCollection $refunds,
    ) {
        $this->set_paid = $this->set_paid ?? false;
        $this->prices_include_tax = $this->prices_include_tax ?? false;
        $this->created_via = $this->created_via ?? '';
    }
}