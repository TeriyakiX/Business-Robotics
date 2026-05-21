<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class MarqueeItemDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $name,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}
}
