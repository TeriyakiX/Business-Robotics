<?php

declare(strict_types=1);

namespace App\Enums;

enum MarqueeItemStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Активен',
            self::INACTIVE => 'Неактивен',
        };
    }
}
