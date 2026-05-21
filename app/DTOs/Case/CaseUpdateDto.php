<?php

declare(strict_types=1);

namespace App\DTOs\Case;

use App\Enums\Case\CaseIndustryEnum;
use App\Traits\DTOs\UseAsArrayTrait;

final readonly class CaseUpdateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $title = null,
        public ?string $client_name = null,
        public ?string $client_role = null,
        public ?string $client_avatar_initials = null,
        public ?CaseIndustryEnum $industry = null,
        public ?array $metrics = null,
        public ?string $description = null,
        public ?string $testimonial = null,
        public ?int $sort_order = null,
        public ?bool $is_visible = null,
    ) {}

    public function toDatabaseArray(): array
    {
        $data = $this->toArray(
            only: [
                'title', 'client_name', 'client_role', 'client_avatar_initials',
                'description', 'metrics', 'testimonial', 'sort_order', 'is_visible'
            ]
        );

        if ($this->industry !== null) {
            $data['industry'] = $this->industry->value;
        }

        return $data;
    }
}
