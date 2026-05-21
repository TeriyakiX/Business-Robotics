<?php

declare(strict_types=1);

namespace App\DTOs\Contact;

use App\Traits\DTOs\UseAsArrayTrait;

final readonly class ContactListDto
{
    use UseAsArrayTrait;

    public function __construct(
        public ?string $search = null,
        public ?string $status = null,
        public ?string $order_by = null,
        public ?string $order_direction = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public ?int $per_page = null,
        public ?int $page = null,
    ) {}
}
