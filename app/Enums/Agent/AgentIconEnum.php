<?php

declare(strict_types=1);

namespace App\Enums\Agent;

enum AgentIconEnum: string
{
    case PHONE = 'phone';
    case BELL = 'bell';
    case MESSAGE = 'message';
    case BOX = 'box';

    public function svgPath(): string
    {
        return match ($this) {
            self::PHONE => 'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6.5-6.5 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.46a16 16 0 0 0 6.45 6.46l1.36-1.35a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z',
            self::BELL => 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0',
            self::MESSAGE => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z',
            self::BOX => 'M22 10v6M2 10l10-5 10 5-10 5zM6 12v5c3 3 9 3 12 0v-5',
        };
    }
}
