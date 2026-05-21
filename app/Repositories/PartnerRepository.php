<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\Partner\PartnerBenefitDto;
use App\DTOs\Partner\PartnerStepDto;
use App\DTOs\Partner\PartnerVariantDto;
use App\Models\PartnerBenefit;
use App\Models\PartnerStep;
use App\Models\PartnerVariant;
use Illuminate\Database\Eloquent\Collection;

final readonly class PartnerRepository
{
    public function getVariants(bool $onlyActive = true): Collection
    {
        $query = PartnerVariant::query()
            ->when($onlyActive, fn($q) => $q->active())
            ->orderBy(PartnerVariant::SORT_ORDER, 'asc'); // Вместо orderBySortOrder

        return $query->get();
    }

    public function getVariantByType(string $type): ?PartnerVariant
    {
        return PartnerVariant::query()
            ->where(PartnerVariant::TYPE, $type)
            ->first();
    }

    public function createVariant(PartnerVariantDto $dto): PartnerVariant
    {
        return PartnerVariant::query()->create($dto->toArray());
    }

    public function getSteps(bool $onlyActive = true): Collection
    {
        $query = PartnerStep::query()
            ->when($onlyActive, fn($q) => $q->active())
            ->orderBy(PartnerStep::SORT_ORDER, 'asc')
            ->orderBy(PartnerStep::NUMBER, 'asc');

        return $query->get();
    }

    public function createStep(PartnerStepDto $dto): PartnerStep
    {
        return PartnerStep::query()->create($dto->toArray());
    }

    public function getBenefits(bool $onlyActive = true): Collection
    {
        $query = PartnerBenefit::query()
            ->when($onlyActive, fn($q) => $q->active())
            ->orderBy(PartnerBenefit::SORT_ORDER, 'asc');

        return $query->get();
    }

    public function createBenefit(PartnerBenefitDto $dto): PartnerBenefit
    {
        return PartnerBenefit::query()->create($dto->toArray());
    }
}
