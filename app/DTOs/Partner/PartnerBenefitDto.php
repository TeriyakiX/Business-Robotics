<?php

declare(strict_types=1);

namespace App\DTOs\Partner;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class PartnerBenefitDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $title,
        public string $description,
        public string $icon_name,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}
}
