<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CustomerRole extends Enum
{
    private const SUPER_ADMIN = 'super_admin';
    private const ADMIN = 'admin';
    private const EDITOR = 'editor';
    private const AUTHOR = 'author';
    private const CONTRIBUTOR = 'contributor';
    private const SUBSCRIBER = 'subscriber';
    private const CUSTOMER = 'customer';
    private const SHOP_MANAGER = 'shop_manager';
}
