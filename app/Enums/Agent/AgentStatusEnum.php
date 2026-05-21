<?php

declare(strict_types=1);

namespace App\Enums\Agent;

enum AgentStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case DRAFT = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Активен',
            self::INACTIVE => 'Неактивен',
            self::DRAFT => 'Черновик',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => '#22c55e',
            self::INACTIVE => '#ef4444',
            self::DRAFT => '#eab308',
        };
    }
}
