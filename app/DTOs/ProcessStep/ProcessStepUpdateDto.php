<?php

declare(strict_types=1);

namespace App\DTOs\ProcessStep;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class ProcessStepUpdateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?int $number = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $day_range = null,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}
}
