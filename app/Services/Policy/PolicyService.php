<?php

declare(strict_types=1);

namespace App\Services\Policy;

use App\DTOs\Policy\PolicyCreateDto;
use App\DTOs\Policy\PolicyUpdateDto;
use App\Models\Policy;

final class PolicyService
{
    public function list(): mixed
    {
        return Policy::query()
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function item(string $id): Policy
    {
        return Policy::query()->findOrFail($id);
    }

    public function showBySlug(string $slug): Policy
    {
        return Policy::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function create(PolicyCreateDto $dto): Policy
    {
        return Policy::create($dto->toArray());
    }

    public function update(string $id, PolicyUpdateDto $dto): Policy
    {
        $model = $this->item($id);

        $model->update(array_filter($dto->toArray(), fn($v) => $v !== null));

        return $model->refresh();
    }

    public function delete(string $id): void
    {
        $this->item($id)->delete();
    }

    public function restore(string $id): void
    {
        Policy::withTrashed()->findOrFail($id)->restore();
    }
}
