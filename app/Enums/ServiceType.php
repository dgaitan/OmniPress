<?php

namespace App\Enums;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ServiceType
{
    public const WOOCOMMERCE = 'woocommerce';

    public static function default()
    {
        return self::WOOCOMMERCE;
    }
}
