<?php

declare(strict_types=1);

namespace App\DTOs\PartnerBenefit;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class PartnerBenefitUpdateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $icon_name = null,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}
}
