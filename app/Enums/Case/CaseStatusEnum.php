<?php

declare(strict_types=1);

namespace App\Enums\Case;

enum CaseStatusEnum: string
{
    case VISIBLE = 'visible';
    case HIDDEN = 'hidden';
    case DRAFT = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::VISIBLE => 'Виден на сайте',
            self::HIDDEN => 'Скрыт',
            self::DRAFT => 'Черновик',
        };
    }
}
