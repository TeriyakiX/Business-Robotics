<?php

declare(strict_types=1);

namespace App\Exceptions\MarqueeItem;

use Exception;

final class MarqueeItemNotFoundException extends Exception
{
    protected $message = 'Marquee item not found';
    protected $code = 404;
}
