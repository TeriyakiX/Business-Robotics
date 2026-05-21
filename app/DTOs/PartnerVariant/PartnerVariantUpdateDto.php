<?php

declare(strict_types=1);

namespace App\DTOs\PartnerVariant;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class PartnerVariantUpdateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $type = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?int $percentage = null,
        public ?int $min_amount = null,
        public ?string $amount_label = null,
        public ?string $badge_color = null,
        public ?string $badge_bg = null,
        public ?array $tags = null,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}
}
