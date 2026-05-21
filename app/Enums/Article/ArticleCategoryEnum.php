<?php

declare(strict_types=1);

namespace App\Enums\Article;

enum ArticleCategoryEnum: string
{
    case AUTOMATION = 'automation';
    case AI_FOR_BUSINESS = 'ai_for_business';
    case HR_AUTOMATION = 'hr_automation';
    case ROBOTS = 'robots';
    case TECHNOLOGY = 'technology';
    case CASE = 'case';

    public function label(): string
    {
        return match ($this) {
            self::AUTOMATION => 'Автоматизация',
            self::AI_FOR_BUSINESS => 'ИИ для бизнеса',
            self::HR_AUTOMATION => 'HR-автоматизация',
            self::ROBOTS => 'Роботы',
            self::TECHNOLOGY => 'Технологии',
            self::CASE => 'Кейс',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AUTOMATION => '#33DAFF',
            self::AI_FOR_BUSINESS => '#A78BFA',
            self::HR_AUTOMATION => '#34D399',
            self::ROBOTS => '#F59E0B',
            self::TECHNOLOGY => '#FF9A3C',
            self::CASE => '#10B981',
        };
    }

    public function bgColor(): string
    {
        return match ($this) {
            self::AUTOMATION => 'rgba(0,207,255,0.08)',
            self::AI_FOR_BUSINESS => 'rgba(167,139,250,0.1)',
            self::HR_AUTOMATION => 'rgba(52,211,153,0.1)',
            self::ROBOTS => 'rgba(245,158,11,0.1)',
            self::TECHNOLOGY => 'rgba(255,120,0,0.08)',
            self::CASE => 'rgba(16,185,129,0.1)',
        };
    }

    public function slug(): string
    {
        return match ($this) {
            self::AUTOMATION => 'avtomatizaciya',
            self::AI_FOR_BUSINESS => 'ii-dlya-biznesa',
            self::HR_AUTOMATION => 'hr-avtomatizaciya',
            self::ROBOTS => 'roboty',
            self::TECHNOLOGY => 'tekhnologii',
            self::CASE => 'keysy',
        };
    }
}
