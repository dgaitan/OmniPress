<?php

namespace App\Models\Concerns;

use Cknow\Money\Money;

trait HasMoney {

    public function getMoneyValue(string $fieldName): string|null {
        if (! array_key_exists($fieldName, $this->attributes)) {
            return null;
        }

        return Money::USD($this->attributes[$fieldName]);
    }
}
