<?php

namespace App\Models\Concerns;

use Cknow\Money\Money;

trait HasMoney
{
    public function getMoneyValue(string $fieldName): Money|null
    {
        if (! array_key_exists($fieldName, $this->attributes)) {
            return null;
        }

        return $this->getAsMoney($this->attributes[$fieldName]);
    }

    /**
     * Get MOney Value
     *
     * @param integer $amount
     * @return object
     */
    public function getAsMoney(int $amount): Money
    {
        return Money::USD($amount);
    }

    /**
     * Parse a value to a integer
     *
     * @param mixed $value
     * @return integer
     */
    public static function valueToMoney(mixed $value): int
    {
        if (is_float($value) || is_string($value)) {
            $value = (int) ((float) $value * 100);
        }

        return $value;
    }
}
