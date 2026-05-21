<?php

declare(strict_types=1);

namespace App\DTOs\Agent;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class AgentListDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $search = null,
        public ?bool $is_active = null,
        public ?string $order_by = null,
        public ?string $order_direction = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public ?int $per_page = null,
        public ?string $cursor = null,
        public ?int $page = null,
        public ?bool $with_features = null,
    ) {}
}
