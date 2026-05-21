<?php

declare(strict_types=1);

namespace App\Enums;

enum DateFormatEnum: string
{
    case API_DATETIME = 'Y-m-d H:i:s';
    case API_DATE = 'Y-m-d';
    case HUMAN_DATETIME = 'd.m.Y H:i';
    case HUMAN_DATE = 'd.m.Y';
    case HUMAN_DATE_RU = 'd M Y';
    case BLOG_DATE = 'd M Y';

    public function getFormat(): string
    {
        return $this->value;
    }

    public static function fromFormat(string $format): ?self
    {
        return match ($format) {
            'Y-m-d H:i:s' => self::API_DATETIME,
            'Y-m-d' => self::API_DATE,
            'd.m.Y H:i' => self::HUMAN_DATETIME,
            'd.m.Y' => self::HUMAN_DATE,
            'd M Y' => self::HUMAN_DATE_RU,
            default => null,
        };
    }
}
