<?php

declare(strict_types=1);

namespace App\Enums\Article;

enum ArticleStatusEnum: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::PUBLISHED => 'Опубликовано',
            self::DRAFT => 'Черновик',
            self::ARCHIVED => 'В архиве',
        };
    }
}
