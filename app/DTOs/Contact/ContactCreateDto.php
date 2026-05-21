<?php

declare(strict_types=1);

namespace App\DTOs\Contact;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class ContactCreateDto
{
    use UseAsArrayTrait;

    public function __construct(
        public string $name,
        public string $phone,
        public ?string $company = null,
        public ?string $status = 'new',
    ) {}

    public function toDatabaseArray(): array
    {
        return $this->toArray(
            only: ['name', 'phone', 'company', 'status']
        );
    }
}
