<?php

declare(strict_types=1);

namespace App\DTOs\MarqueeItem;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class MarqueeItemCreateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $name,
        public int $sort_order,
        public bool $is_active,
    ) {}
}
