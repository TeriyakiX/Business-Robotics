<?php

declare(strict_types=1);

namespace App\Enums\Partner;

enum PartnerVariantTypeEnum: string
{
    case DEVELOPMENT = 'development';
    case SUBSCRIPTION = 'subscription';

    public function label(): string
    {
        return match ($this) {
            self::DEVELOPMENT => 'Разработка продукта',
            self::SUBSCRIPTION => 'Подписка клиента',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::DEVELOPMENT => '#005FAA',
            self::SUBSCRIPTION => '#7C3AED',
        };
    }

    public function badgeBg(): string
    {
        return match ($this) {
            self::DEVELOPMENT => 'rgba(0,207,255,0.08)',
            self::SUBSCRIPTION => 'rgba(167,139,250,0.1)',
        };
    }
}
