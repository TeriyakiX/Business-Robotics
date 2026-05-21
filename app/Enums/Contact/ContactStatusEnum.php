<?php

declare(strict_types=1);

namespace App\Enums\Contact;

enum ContactStatusEnum: string
{
    case NEW = 'new';
    case PROCESSED = 'processed';
    case CONTACTED = 'contacted';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Новая',
            self::PROCESSED => 'В обработке',
            self::CONTACTED => 'Связались',
            self::REJECTED => 'Отклонена',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => '#eab308',
            self::PROCESSED => '#3b82f6',
            self::CONTACTED => '#22c55e',
            self::REJECTED => '#ef4444',
        };
    }

    public function bgColor(): string
    {
        return match ($this) {
            self::NEW => 'rgba(234,179,8,0.1)',
            self::PROCESSED => 'rgba(59,130,246,0.1)',
            self::CONTACTED => 'rgba(34,197,94,0.1)',
            self::REJECTED => 'rgba(239,68,68,0.1)',
        };
    }
}
