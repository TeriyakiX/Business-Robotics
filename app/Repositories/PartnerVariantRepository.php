<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\PartnerVariant\PartnerVariantCreateDto;
use App\DTOs\PartnerVariant\PartnerVariantUpdateDto;
use App\Models\PartnerVariant;
use Illuminate\Database\Eloquent\Collection;

final readonly class PartnerVariantRepository
{
    public function getAll(?string $type = null, bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        $query = PartnerVariant::query();

        if ($onlyActive) {
            $query->active();
        }

        if ($isActive !== null) {
            $query->where(PartnerVariant::IS_ACTIVE, $isActive);
        }

        if ($type) {
            $query->byType($type);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where(PartnerVariant::TITLE, 'like', "%{$search}%")
                    ->orWhere(PartnerVariant::DESCRIPTION, 'like', "%{$search}%");
            });
        }

        return $query->ordered()->get();
    }

    public function findById(string $id): ?PartnerVariant
    {
        return PartnerVariant::query()->find($id);
    }

    public function create(PartnerVariantCreateDto $dto): PartnerVariant
    {
        return PartnerVariant::query()->create($dto->toArray());
    }

    public function update(PartnerVariant $item, PartnerVariantUpdateDto $dto): ?PartnerVariant
    {
        $data = array_filter($dto->toArray(), fn($value) => $value !== null);

        if (empty($data)) {
            return $item;
        }

        $result = $item->update($data);

        return $result ? $item->fresh() : null;
    }

    public function delete(PartnerVariant $item): bool
    {
        return $item->delete();
    }
}
