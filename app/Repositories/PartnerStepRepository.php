<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\PartnerStep\PartnerStepCreateDto;
use App\DTOs\PartnerStep\PartnerStepUpdateDto;
use App\Models\PartnerStep;
use Illuminate\Database\Eloquent\Collection;

final readonly class PartnerStepRepository
{
    public function getAll(bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        $query = PartnerStep::query();

        if ($onlyActive) {
            $query->active();
        }

        if ($isActive !== null) {
            $query->where(PartnerStep::IS_ACTIVE, $isActive);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where(PartnerStep::TITLE, 'like', "%{$search}%")
                    ->orWhere(PartnerStep::DESCRIPTION, 'like', "%{$search}%");
            });
        }

        return $query->ordered()->get();
    }

    public function findById(string $id): ?PartnerStep
    {
        return PartnerStep::query()->find($id);
    }

    public function create(PartnerStepCreateDto $dto): PartnerStep
    {
        return PartnerStep::query()->create($dto->toArray());
    }

    public function update(PartnerStep $item, PartnerStepUpdateDto $dto): ?PartnerStep
    {
        $data = array_filter($dto->toArray(), fn($value) => $value !== null);

        if (empty($data)) {
            return $item;
        }

        $result = $item->update($data);

        return $result ? $item->fresh() : null;
    }

    public function delete(PartnerStep $item): bool
    {
        return $item->delete();
    }
}
