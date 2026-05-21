<?php

declare(strict_types=1);

namespace App\DTOs\PartnerBenefit;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class PartnerBenefitCreateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $title,
        public string $description,
        public string $icon_name,
        public ?int $sort_order = 0,
        public ?bool $is_active = true,
    ) {}
}
