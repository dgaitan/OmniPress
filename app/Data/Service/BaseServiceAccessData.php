<?php

namespace App\Data\Service;

use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Crypt;

abstract class BaseServiceAccessData extends Data {
    
    public function getDataToStore() : string {
        return Crypt::encryptString($this->toJson());
    }

    public static function fromEncryptedData(string $accessData) : static {
        $accessData = Crypt::decryptString($accessData);
        return static::fromJson($accessData);
    }

    public static function fromJson(string $accessData) : static {
        $accessData = json_decode($accessData, true);

        return static::from($accessData);
    }
}