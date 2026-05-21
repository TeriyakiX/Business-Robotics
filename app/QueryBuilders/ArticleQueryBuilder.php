<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enums\Article\ArticleCategoryEnum;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;

final class ArticleQueryBuilder extends Builder
{
    public function wherePublished(): self
    {
        return $this->where(Article::IS_PUBLISHED, true)
            ->where(Article::PUBLISHED_AT, '<=', now());
    }

    public function whereDraft(): self
    {
        return $this->where(Article::IS_PUBLISHED, false);
    }

    public function whereCategory(ArticleCategoryEnum $category): self
    {
        return $this->where(Article::CATEGORY, $category->value);
    }

    public function orderByRecent(string $direction = 'desc'): self
    {
        return $this->orderBy(Article::PUBLISHED_AT, $direction);
    }

    public function orderByPopular(string $direction = 'desc'): self
    {
        return $this->orderBy(Article::VIEWS_COUNT, $direction);
    }

    public function search(string $search): self
    {
        return $this->where(function ($query) use ($search) {
            $query->where(Article::TITLE, 'like', "%{$search}%")
                ->orWhere(Article::DESCRIPTION, 'like', "%{$search}%")
                ->orWhere(Article::CONTENT, 'like', "%{$search}%");
        });
    }

    public function whereSlug(string $slug): self
    {
        return $this->where(Article::SLUG, $slug);
    }
}
