<?php

declare(strict_types=1);

namespace App\Services\PartnerStep;

use App\DTOs\PartnerStep\PartnerStepCreateDto;
use App\DTOs\PartnerStep\PartnerStepUpdateDto;
use App\Exceptions\PartnerStep\PartnerStepNotFoundException;
use App\Models\PartnerStep;
use App\Repositories\PartnerStepRepository;
use Illuminate\Database\Eloquent\Collection;

final readonly class PartnerStepService
{
    public function __construct(
        private PartnerStepRepository $repository,
    ) {}

    public function list(bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        return $this->repository->getAll($onlyActive, $search, $isActive);
    }

    /**
     * @throws PartnerStepNotFoundException
     */
    public function item(string $id): PartnerStep
    {
        $item = $this->repository->findById($id);

        if (!$item) {
            throw new PartnerStepNotFoundException();
        }

        return $item;
    }

    public function create(PartnerStepCreateDto $dto): PartnerStep
    {
        return $this->repository->create($dto);
    }

    /**
     * @throws PartnerStepNotFoundException
     */
    public function update(string $id, PartnerStepUpdateDto $dto): PartnerStep
    {
        $item = $this->item($id);
        $updated = $this->repository->update($item, $dto);

        if (!$updated) {
            throw new PartnerStepNotFoundException();
        }

        return $updated;
    }

    /**
     * @throws PartnerStepNotFoundException
     */
    public function delete(string $id): bool
    {
        $item = $this->item($id);

        return $this->repository->delete($item);
    }
}
