<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatus extends Enum
{
    private const PENDING = 'pending';

    private const PROCESSING = 'processing';

    private const ON_HOLD = 'on-hold';

    private const COMPLETED = 'completed';

    private const CANCELLED = 'cancelled';

    private const REFUNDED = 'refunded';

    private const FAILED = 'failed';

    private const TRASH = 'trash';

    public static function default()
    {
        return self::PENDING();
    }
}
