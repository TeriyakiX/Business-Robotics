<?php

declare(strict_types=1);

namespace App\Enums\Case;

enum CaseIndustryEnum: string
{
    case MEDICINE = 'medicine';
    case CALL_CENTER = 'call_center';
    case BEAUTY = 'beauty';
    case FITNESS = 'fitness';
    case LEGAL = 'legal';
    case REAL_ESTATE = 'real_estate';
    case ECOMMERCE = 'ecommerce';
    case EDUCATION = 'education';

    public function label(): string
    {
        return match ($this) {
            self::MEDICINE => 'Медицина',
            self::CALL_CENTER => 'Колл-центр',
            self::BEAUTY => 'Красота',
            self::FITNESS => 'Фитнес',
            self::LEGAL => 'Юриспруденция',
            self::REAL_ESTATE => 'Недвижимость',
            self::ECOMMERCE => 'E-commerce',
            self::EDUCATION => 'Образование',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::MEDICINE => 'stethoscope',
            self::CALL_CENTER => 'headphones',
            self::BEAUTY => 'sparkles',
            self::FITNESS => 'activity',
            self::LEGAL => 'scale',
            self::REAL_ESTATE => 'home',
            self::ECOMMERCE => 'shopping-cart',
            self::EDUCATION => 'book',
        };
    }
}
