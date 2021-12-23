<?php

namespace App\Data\Http;

use Spatie\LaravelData\DataCollection;
use App\Data\BaseData;
use App\Data\Coupon\CouponSettingData;

class CouponData extends BaseData {
    public static $id_field = 'coupon_id';

    protected static $collectionFields = [
        'meta_data' => \App\Data\Shared\MetaData::class
    ];
    
    public function __construct(
        public int $coupon_id,
        public string $code,
        public string $date_created,
        public ?string $date_expires,
        public string $date_modified,
        public bool $individual_use,
        public int $amount,
        public ?string $description,
        public ?string $discount_type,
        public ?CouponSettingData $settings,
        /** @var \App\Data\Coupon\CouponMetaData[] */
        public ?DataCollection $meta_data 
    ) {

    }

    /**
     * Process the http response and return it validated
     * to convert in a DataClass
     * 
     * @param array $data - Http Response
     * @return array Data ready to be a DataClass
     */
    public static function _processResponse(array $data): array {
        $_data = parent::_processResponse($data);
        $_data['settings'] = CouponSettingData::_fromResponse($data);
        
        return $_data;
    }
}