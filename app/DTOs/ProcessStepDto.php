<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class ProcessStepDto
{
    use UseAsArrayTrait;

    public function __construct(
        public int $number,
        public string $title,
        public string $description,
        public string $day_range,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}
}
