<?php

declare(strict_types=1);

namespace App\DTOs\Article;

use App\Enums\Article\ArticleCategoryEnum;
use App\Traits\DTOs\UseAsArrayTrait;

final readonly class ArticleListDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $search = null,
        public ?ArticleCategoryEnum $category = null,
        public ?bool $is_published = null,
        public ?string $order_by = null,
        public ?string $order_direction = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public ?int $per_page = null,
        public ?int $page = null,
        public ?string $cursor = null,
        public ?bool $with_content = null,
    ) {}
}
