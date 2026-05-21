<?php

declare(strict_types=1);

namespace App\DTOs\Contact;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class ContactUpdateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $status = null,
        public ?string $notes = null,
        public ?string $processed_at = null,
    ) {}

    public function toDatabaseArray(): array
    {
        return $this->toArray(
            only: ['status', 'notes', 'processed_at']
        );
    }
}
