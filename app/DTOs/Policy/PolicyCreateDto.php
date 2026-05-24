<?php

declare(strict_types=1);

namespace App\DTOs\Policy;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class PolicyCreateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $title,
        public string $slug,
        public string $content,
        public ?bool $is_active = true,
    ) {}
}
