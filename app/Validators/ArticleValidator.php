<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\Article\ArticleNotFoundException;
use App\Exceptions\Article\ArticlePublishForbiddenException;
use App\Exceptions\Article\ArticleSlugExistsException;
use App\Models\Article;
use Carbon\Carbon;

final readonly class ArticleValidator
{
    public function validateArticleExists(?Article $article): void
    {
        if (!$article) {
            throw new ArticleNotFoundException();
        }
    }

    public function validateSlugUnique(string $slug, ?string $excludeId = null): void
    {
        $query = Article::query()->where(Article::SLUG, $slug);

        if ($excludeId) {
            $query->where(Article::ID, '!=', $excludeId);
        }

        if ($query->exists()) {
            throw new ArticleSlugExistsException();
        }
    }

    public function validateCanPublish(?string $publishedAt): void
    {
        // throw new ArticlePublishForbiddenException();
    }
}
