<?php

declare(strict_types=1);

namespace App\DTOs\Agent;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class AgentCreateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $name,
        public string $tag,
        public string $description,
        public ?array $features = null,
        public ?string $icon_name = null,
        public ?int $sort_order = null,
        public ?bool $is_active = null,
    ) {}

    public function toDatabaseArray(): array
    {
        return $this->toArray(
            only: ['name', 'tag', 'description', 'features', 'icon_name', 'sort_order', 'is_active']
        );
    }
}
