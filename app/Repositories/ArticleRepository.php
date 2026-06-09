<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\Article\ArticleListDto;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final readonly class ArticleRepository
{
    public function list(ArticleListDto $dto): Collection|CursorPaginator
    {
        $query = Article::query()
            ->when($dto->search, fn($q) => $q->search($dto->search))
            ->when($dto->category_slug, function ($q) use ($dto) {
                $category = Category::where('slug', $dto->category_slug)->first();
                if ($category) {
                    return $q->where('category_id', $category->id);
                }
                return $q;
            })
            ->when($dto->is_published !== null, function ($q) use ($dto) {
                return $dto->is_published ? $q->wherePublished() : $q->whereDraft();
            })
            ->when($dto->order_by === 'recent', fn($q) => $q->orderByRecent($dto->order_direction ?? 'desc'))
            ->when($dto->order_by === 'popular', fn($q) => $q->orderByPopular($dto->order_direction ?? 'desc'))
            ->when($dto->order_by && !in_array($dto->order_by, ['recent', 'popular']), function ($q) use ($dto) {
                $direction = $dto->order_direction ?? 'desc';
                return $q->orderBy($dto->order_by, $direction);
            });

        if ($dto->per_page) {
            return $query->cursorPaginate($dto->per_page, ['*'], 'cursor', $dto->cursor);
        }

        if ($dto->limit) {
            $query->limit($dto->limit);
        }

        if ($dto->offset) {
            $query->offset($dto->offset);
        }

        return $query->get();
    }

    public function item(string $id): ?Article
    {
        return Article::query()->find($id);
    }

    public function findBySlug(string $slug): ?Article
    {
        return Article::query()->where('slug', $slug)->first();
    }

    public function create(array $data): Article
    {
        return DB::transaction(function () use ($data) {
            return Article::query()->create($data);
        });
    }

    public function update(Article $article, array $data): ?Article
    {
        $result = $article->update($data);
        return $result ? $article->fresh() : null;
    }

    public function delete(Article $article): bool
    {
        return $article->delete();
    }

    public function forceDelete(Article $article): bool
    {
        return $article->forceDelete();
    }

    public function restore(Article $article): bool
    {
        return $article->restore();
    }
}
