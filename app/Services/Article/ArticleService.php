<?php

declare(strict_types=1);

namespace App\Services\Article;

use App\DTOs\Article\ArticleCreateDto;
use App\DTOs\Article\ArticleListDto;
use App\DTOs\Article\ArticleUpdateDto;
use Illuminate\Http\UploadedFile;
use App\Models\Article;
use App\Models\Category;
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
        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Получить или создать категорию по slug
     */
    private function findOrCreateCategory(?string $slug, ?string $title = null): ?Category
    {
        if (empty($slug)) {
            return null;
        }

        $category = Category::where('slug', $slug)->first();

        if ($category) {
            return $category;
        }

        $categoryName = $title ?? $slug;
        $displayName = ucfirst(str_replace('_', ' ', $categoryName));

        return Category::create([
            'slug' => $slug,
            'name' => $displayName,
            'color' => '#00CFFF',
            'bg_color' => 'rgba(0,207,255,0.08)',
        ]);
    }

    public function create(ArticleCreateDto $dto): Article
    {
        $slug = $dto->slug ?: Str::slug($dto->title);
        $this->validator->validateSlugUnique($slug);

        // Находим или создаём категорию
        $category = $this->findOrCreateCategory($dto->category_slug, $dto->title);
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
            'slug'             => $slug,
            'title'            => $dto->title,
            'category_id'      => $category?->id,
            'description'      => $dto->description,
            'content'          => $dto->content,
            'reading_time'     => $dto->reading_time ?? (int) ceil(str_word_count(strip_tags($dto->content)) / 200),
            'published_at'     => $publishedAt,
            'is_published'     => $dto->is_published ?? false,
            'cover_path'       => $coverPath,
            'gallery'          => $galleryPaths,
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
        if ($dto->slug !== null)             $updateData['slug']             = $dto->slug;
        if ($dto->title !== null)            $updateData['title']            = $dto->title;
        if ($dto->description !== null)      $updateData['description']      = $dto->description;
        if ($dto->content !== null)          $updateData['content']          = $dto->content;
        if ($dto->reading_time !== null)     $updateData['reading_time']     = $dto->reading_time;
        if ($publishedAt !== null)           $updateData['published_at']     = $publishedAt;
        if ($dto->is_published !== null)     $updateData['is_published']     = $dto->is_published;

        if ($dto->category_slug !== null) {
            $category = $this->findOrCreateCategory($dto->category_slug, $dto->title);
            $updateData['category_id'] = $category?->id;
        }

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
