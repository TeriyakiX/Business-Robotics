<?php

declare(strict_types=1);

namespace App\DTOs\MarqueeItem;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class MarqueeItemUpdateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $name = null,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}
}
