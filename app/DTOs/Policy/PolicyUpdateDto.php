<?php

declare(strict_types=1);

namespace App\DTOs\Policy;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class PolicyUpdateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $title = null,
        public ?string $slug = null,
        public ?string $content = null,
        public ?bool $is_active = null,
    ) {}
}
