<?php

declare(strict_types=1);

namespace App\Services\ProcessStep;

use App\DTOs\ProcessStep\ProcessStepCreateDto;
use App\DTOs\ProcessStep\ProcessStepUpdateDto;
use App\Exceptions\ProcessStep\ProcessStepNotFoundException;
use App\Models\ProcessStep;
use App\Repositories\ProcessStepRepository;
use Illuminate\Database\Eloquent\Collection;

final readonly class ProcessStepService
{
    public function __construct(
        private ProcessStepRepository $repository,
    ) {}

    public function list(bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        return $this->repository->getAll($onlyActive, $search, $isActive);
    }

    /**
     * @throws ProcessStepNotFoundException
     */
    public function item(string $id): ProcessStep
    {
        $item = $this->repository->findById($id);

        if (!$item) {
            throw new ProcessStepNotFoundException();
        }

        return $item;
    }

    public function create(ProcessStepCreateDto $dto): ProcessStep
    {
        return $this->repository->create($dto);
    }

    /**
     * @throws ProcessStepNotFoundException
     */
    public function update(string $id, ProcessStepUpdateDto $dto): ProcessStep
    {
        $item = $this->item($id);
        $updated = $this->repository->update($item, $dto);

        if (!$updated) {
            throw new ProcessStepNotFoundException();
        }

        return $updated;
    }

    /**
     * @throws ProcessStepNotFoundException
     */
    public function delete(string $id): bool
    {
        $item = $this->item($id);

        return $this->repository->delete($item);
    }
}
