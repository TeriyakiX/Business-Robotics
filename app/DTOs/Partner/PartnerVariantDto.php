<?php

declare(strict_types=1);

namespace App\DTOs\Partner;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class PartnerVariantDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $type,
        public string $title,
        public string $description,
        public int $percentage,
        public int $min_amount,
        public string $amount_label,
        public ?string $badge_color = null,
        public ?string $badge_bg = null,
        public ?array $tags = null,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}
}
