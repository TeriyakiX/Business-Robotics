<?php

declare(strict_types=1);

namespace App\Services\Article;

use App\DTOs\Article\ArticleCreateDto;
use App\DTOs\Article\ArticleListDto;
use App\DTOs\Article\ArticleUpdateDto;
use Illuminate\Http\UploadedFile;
use App\Exceptions\Article\ArticleNotFoundException;
use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Validators\ArticleValidator;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

final readonly class ArticleService
{
    public function __construct(
        private ArticleRepository $repository,
        private ArticleValidator $validator,
    ) {}

    public function list(ArticleListDto $dto): Collection|CursorPaginator
    {
        return $this->repository->list($dto);
    }

    public function item(string $id): Article
    {
        $article = $this->repository->item($id);
        $this->validator->validateArticleExists($article);
        return $article;
    }

    public function findBySlug(string $slug): Article
    {
        $article = $this->repository->findBySlug($slug);
        $this->validator->validateArticleExists($article);
        return $article;
    }

    private function saveFile(UploadedFile $file, string $folder): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');
        return $path;
    }

    public function create(ArticleCreateDto $dto): Article
    {
        $slug = $dto->slug ?: Str::slug($dto->title);
        $this->validator->validateSlugUnique($slug);

        $publishedAt = $dto->published_at;
        if ($dto->is_published && !$publishedAt) {
            $publishedAt = Carbon::now()->toDateTimeString();
        }

        $coverPath = null;
        if ($dto->hasCover() && $dto->cover instanceof UploadedFile) {
            $coverPath = $this->saveFile($dto->cover, 'articles/covers');
        }

        $galleryPaths = [];
        if ($dto->hasGallery() && is_array($dto->gallery)) {
            foreach ($dto->gallery as $image) {
                if ($image instanceof UploadedFile && $image->isValid()) {
                    $galleryPaths[] = $this->saveFile($image, 'articles/gallery');
                }
            }
        }

        $createData = [
            'slug' => $slug,
            'title' => $dto->title,
            'category' => $dto->category->value,
            'category_color' => $dto->category_color,
            'category_bg_color' => $dto->category_bg_color,
            'description' => $dto->description,
            'content' => $dto->content,
            'reading_time' => $dto->reading_time ?? ceil(str_word_count(strip_tags($dto->content)) / 200),
            'published_at' => $publishedAt,
            'is_published' => $dto->is_published ?? false,
            'cover_path' => $coverPath,
            'gallery' => $galleryPaths,
        ];

        return $this->repository->create($createData);
    }

    public function update(string $id, ArticleUpdateDto $dto): Article
    {
        $article = $this->repository->item($id);
        $this->validator->validateArticleExists($article);

        if ($dto->slug && $dto->slug !== $article->slug) {
            $this->validator->validateSlugUnique($dto->slug, $id);
        }

        $publishedAt = $dto->published_at;
        if ($dto->is_published && !$article->published_at && !$publishedAt) {
            $publishedAt = Carbon::now()->toDateTimeString();
        }

        $updateData = [];
        if ($dto->slug !== null) $updateData['slug'] = $dto->slug;
        if ($dto->title !== null) $updateData['title'] = $dto->title;
        if ($dto->category !== null) $updateData['category'] = $dto->category->value;
        if ($dto->category_color !== null) $updateData['category_color'] = $dto->category_color;
        if ($dto->category_bg_color !== null) $updateData['category_bg_color'] = $dto->category_bg_color;
        if ($dto->description !== null) $updateData['description'] = $dto->description;
        if ($dto->content !== null) $updateData['content'] = $dto->content;
        if ($dto->reading_time !== null) $updateData['reading_time'] = $dto->reading_time;
        if ($publishedAt !== null) $updateData['published_at'] = $publishedAt;
        if ($dto->is_published !== null) $updateData['is_published'] = $dto->is_published;

        if ($dto->shouldDeleteCover()) {
            if ($article->cover_path) {
                Storage::disk('public')->delete($article->cover_path);
            }
            $updateData['cover_path'] = null;
        } elseif ($dto->hasCover() && $dto->cover instanceof UploadedFile) {
            if ($article->cover_path) {
                Storage::disk('public')->delete($article->cover_path);
            }
            $updateData['cover_path'] = $this->saveFile($dto->cover, 'articles/covers');
        }

        if ($dto->hasGallery() && is_array($dto->gallery)) {
            if ($article->gallery) {
                foreach ($article->gallery as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $galleryPaths = [];
            foreach ($dto->gallery as $image) {
                if ($image instanceof UploadedFile && $image->isValid()) {
                    $galleryPaths[] = $this->saveFile($image, 'articles/gallery');
                }
            }
            $updateData['gallery'] = $galleryPaths;
        }

        if ($dto->increment_views) {
            $article->incrementViews();
        }

        if (!empty($updateData)) {
            $this->repository->update($article, $updateData);
        }

        return $article->fresh();
    }

    public function delete(string $id): bool
    {
        $article = $this->repository->item($id);
        $this->validator->validateArticleExists($article);

        if ($article->cover_path) {
            Storage::disk('public')->delete($article->cover_path);
        }
        if ($article->gallery) {
            foreach ($article->gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        return $this->repository->delete($article);
    }

    public function restore(string $id): bool
    {
        $article = $this->repository->item($id);
        $this->validator->validateArticleExists($article);
        return $this->repository->restore($article);
    }

    public function incrementViews(string $slug): Article
    {
        $article = $this->findBySlug($slug);
        $article->incrementViews();
        return $article->fresh();
    }
}
