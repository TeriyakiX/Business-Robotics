<?php

declare(strict_types=1);

namespace App\Services\Case;

use App\DTOs\Case\CaseCreateDto;
use App\DTOs\Case\CaseListDto;
use App\DTOs\Case\CaseUpdateDto;
use App\Exceptions\Case\CaseCreateForbiddenException;
use App\Exceptions\Case\CaseNotFoundException;
use App\Models\BusinessCase;
use App\Repositories\CaseRepository;
use App\Validators\CaseValidator;
use Illuminate\Database\Eloquent\Collection;

final readonly class CaseService
{
    public function __construct(
        private CaseRepository $repository,
        private CaseValidator $validator,
    ) {}

    public function list(CaseListDto $dto): Collection
    {
        return $this->repository->list($dto);
    }

    /**
     * @throws CaseNotFoundException
     */
    public function item(string $id): BusinessCase
    {
        $case = $this->repository->item($id);
        $this->validator->validateCaseExists($case);

        return $case;
    }

    /**
     * @throws CaseCreateForbiddenException
     */
    public function create(CaseCreateDto $dto): BusinessCase
    {
        if ($dto->metrics) {
            $this->validator->validateMetrics($dto->metrics);
        }

        return $this->repository->create($dto);
    }

    /**
     * @throws CaseNotFoundException
     */
    public function update(string $id, CaseUpdateDto $dto): BusinessCase
    {
        $case = $this->repository->item($id);
        $this->validator->validateCaseExists($case);

        if ($dto->metrics) {
            $this->validator->validateMetrics($dto->metrics);
        }

        $updated = $this->repository->update($case, $dto);

        if (!$updated) {
            throw new CaseNotFoundException();
        }

        return $updated;
    }

    /**
     * @throws CaseNotFoundException
     */
    public function delete(string $id): bool
    {
        $case = $this->repository->item($id);
        $this->validator->validateCaseExists($case);

        return $this->repository->delete($case);
    }

    public function restore(string $id): bool
    {
        $case = $this->repository->item($id);
        $this->validator->validateCaseExists($case);

        return $this->repository->restore($case);
    }
}
