<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\PartnerBenefit\PartnerBenefitCreateDto;
use App\DTOs\PartnerBenefit\PartnerBenefitUpdateDto;
use App\Models\PartnerBenefit;
use Illuminate\Database\Eloquent\Collection;

final readonly class PartnerBenefitRepository
{
    public function getAll(bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        $query = PartnerBenefit::query();

        if ($onlyActive) {
            $query->active();
        }

        if ($isActive !== null) {
            $query->where(PartnerBenefit::IS_ACTIVE, $isActive);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where(PartnerBenefit::TITLE, 'like', "%{$search}%")
                    ->orWhere(PartnerBenefit::DESCRIPTION, 'like', "%{$search}%");
            });
        }

        return $query->ordered()->get();
    }

    public function findById(string $id): ?PartnerBenefit
    {
        return PartnerBenefit::query()->find($id);
    }

    public function create(PartnerBenefitCreateDto $dto): PartnerBenefit
    {
        return PartnerBenefit::query()->create($dto->toArray());
    }

    public function update(PartnerBenefit $item, PartnerBenefitUpdateDto $dto): ?PartnerBenefit
    {
        $data = array_filter($dto->toArray(), fn($value) => $value !== null);

        if (empty($data)) {
            return $item;
        }

        $result = $item->update($data);

        return $result ? $item->fresh() : null;
    }

    public function delete(PartnerBenefit $item): bool
    {
        return $item->delete();
    }
}
