<?php

declare(strict_types=1);

namespace App\DTOs\Case;

use App\Enums\Case\CaseIndustryEnum;
use App\Traits\DTOs\UseAsArrayTrait;

final readonly class CaseListDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $search = null,
        public ?CaseIndustryEnum $industry = null,
        public ?bool $is_visible = null,
        public ?string $order_by = null,
        public ?string $order_direction = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public ?int $per_page = null,
        public ?int $page = null,
    ) {}
}
