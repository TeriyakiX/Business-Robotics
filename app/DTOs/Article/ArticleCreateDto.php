<?php

declare(strict_types=1);

namespace App\DTOs\Article;

use App\Traits\DTOs\UseAsArrayTrait;
use Illuminate\Http\UploadedFile;

final readonly class ArticleCreateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $slug,
        public string $title,
        public string $category_slug,
        public string $description = '',
        public string $content = '',
        public ?int $reading_time = null,
        public ?string $published_at = null,
        public ?bool $is_published = false,
        public ?UploadedFile $cover = null,
        public ?array $gallery = null,
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
}
