<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\Contact\ContactCreateDto;
use App\DTOs\Contact\ContactListDto;
use App\DTOs\Contact\ContactUpdateDto;
use App\Models\ContactRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final readonly class ContactRepository
{
    public function list(ContactListDto $dto): Collection
    {
        $query = ContactRequest::query()
            ->when($dto->search, function ($q) use ($dto) {
                return $q->where(function ($query) use ($dto) {
                    $query->where(ContactRequest::NAME, 'like', "%{$dto->search}%")
                        ->orWhere(ContactRequest::PHONE, 'like', "%{$dto->search}%")
                        ->orWhere(ContactRequest::COMPANY, 'like', "%{$dto->search}%");
                });
            })
            ->when($dto->status, fn($q) => $q->where(ContactRequest::STATUS, $dto->status))
            ->when($dto->order_by, fn($q) => $q->orderBy($dto->order_by, $dto->order_direction ?? 'desc'))
            ->when($dto->limit, fn($q) => $q->limit($dto->limit))
            ->when($dto->offset, fn($q) => $q->offset($dto->offset));

        return $query->get();
    }

    public function item(string $id): ?ContactRequest
    {
        return ContactRequest::query()->find($id);
    }

    public function create(ContactCreateDto $dto): ContactRequest
    {
        return DB::transaction(function () use ($dto) {
            return ContactRequest::query()->create($dto->toDatabaseArray());
        });
    }

    public function update(ContactRequest $contact, ContactUpdateDto $dto): ?ContactRequest
    {
        $data = array_filter($dto->toDatabaseArray(), fn($value) => $value !== null);

        if (empty($data)) {
            return $contact;
        }

        $result = $contact->update($data);

        return $result ? $contact->fresh() : null;
    }

    public function delete(ContactRequest $contact): bool
    {
        return $contact->delete();
    }
}
