<?php

declare(strict_types=1);

namespace App\DTOs\PartnerStep;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class PartnerStepCreateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public int $number,
        public string $title,
        public string $description,
        public ?int $sort_order = 0,
        public ?bool $is_active = true,
    ) {}
}
