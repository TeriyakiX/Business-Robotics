<?php

declare(strict_types=1);

namespace App\Services\PartnerBenefit;

use App\DTOs\PartnerBenefit\PartnerBenefitCreateDto;
use App\DTOs\PartnerBenefit\PartnerBenefitUpdateDto;
use App\Exceptions\PartnerBenefit\PartnerBenefitNotFoundException;
use App\Models\PartnerBenefit;
use App\Repositories\PartnerBenefitRepository;
use Illuminate\Database\Eloquent\Collection;

final readonly class PartnerBenefitService
{
    public function __construct(
        private PartnerBenefitRepository $repository,
    ) {}

    public function list(bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        return $this->repository->getAll($onlyActive, $search, $isActive);
    }

    /**
     * @throws PartnerBenefitNotFoundException
     */
    public function item(string $id): PartnerBenefit
    {
        $item = $this->repository->findById($id);

        if (!$item) {
            throw new PartnerBenefitNotFoundException();
        }

        return $item;
    }

    public function create(PartnerBenefitCreateDto $dto): PartnerBenefit
    {
        return $this->repository->create($dto);
    }

    /**
     * @throws PartnerBenefitNotFoundException
     */
    public function update(string $id, PartnerBenefitUpdateDto $dto): PartnerBenefit
    {
        $item = $this->item($id);
        $updated = $this->repository->update($item, $dto);

        if (!$updated) {
            throw new PartnerBenefitNotFoundException();
        }

        return $updated;
    }

    /**
     * @throws PartnerBenefitNotFoundException
     */
    public function delete(string $id): bool
    {
        $item = $this->item($id);

        return $this->repository->delete($item);
    }
}
