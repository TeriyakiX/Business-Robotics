<?php

declare(strict_types=1);

namespace App\DTOs\Article;

use App\Traits\DTOs\UseAsArrayTrait;
use Illuminate\Http\UploadedFile;

final readonly class ArticleUpdateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $slug = null,
        public ?string $title = null,
        public ?string $category_slug = null,
        public ?string $description = null,
        public ?string $content = null,
        public ?int $reading_time = null,
        public ?string $published_at = null,
        public ?bool $is_published = null,
        public ?bool $increment_views = null,
        public ?UploadedFile $cover = null,
        public ?array $gallery = null,
        public ?bool $delete_cover = false,
    ) {}

    public function toDatabaseArray(): array
    {
        return $this->toArray(
            only: [
                'slug', 'title', 'description', 'content', 'reading_time', 'published_at', 'is_published',
            ]
        );
    }

    public function hasCover(): bool
    {
        return $this->cover !== null;
    }

    public function hasGallery(): bool
    {
        return !empty($this->gallery);
    }

    public function shouldDeleteCover(): bool
    {
        return $this->delete_cover === true;
    }
}
