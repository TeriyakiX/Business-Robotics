<?php

declare(strict_types=1);

namespace App\DTOs\Case;

use App\Enums\Case\CaseIndustryEnum;
use App\Traits\DTOs\UseAsArrayTrait;

final readonly class CaseCreateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $title,
        public string $client_name,
        public string $client_role,
        public ?string $client_avatar_initials,
        public CaseIndustryEnum $industry,
        public array $metrics,
        public string $description,
        public ?string $testimonial = null,
        public ?int $sort_order = null,
        public ?bool $is_visible = null,
    ) {}

    public function toDatabaseArray(): array
    {
        return $this->toArray(
            only: [
                'title', 'client_name', 'client_role', 'client_avatar_initials',
                'industry', 'metrics', 'description', 'testimonial', 'sort_order', 'is_visible'
            ]
        );
    }
}
