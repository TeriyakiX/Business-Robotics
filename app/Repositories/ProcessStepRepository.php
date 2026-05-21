<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\ProcessStep\ProcessStepCreateDto;
use App\DTOs\ProcessStep\ProcessStepUpdateDto;
use App\Models\ProcessStep;
use Illuminate\Database\Eloquent\Collection;

final readonly class ProcessStepRepository
{
    public function getAll(bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        $query = ProcessStep::query();

        if ($onlyActive) {
            $query->active();
        }

        if ($isActive !== null) {
            $query->where(ProcessStep::IS_ACTIVE, $isActive);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where(ProcessStep::TITLE, 'like', "%{$search}%")
                    ->orWhere(ProcessStep::DESCRIPTION, 'like', "%{$search}%");
            });
        }

        return $query->ordered()->get();
    }

    public function findById(string $id): ?ProcessStep
    {
        return ProcessStep::query()->find($id);
    }

    public function create(ProcessStepCreateDto $dto): ProcessStep
    {
        return ProcessStep::query()->create($dto->toArray());
    }

    public function update(ProcessStep $item, ProcessStepUpdateDto $dto): ?ProcessStep
    {
        $data = array_filter($dto->toArray(), fn($value) => $value !== null);

        if (empty($data)) {
            return $item;
        }

        $result = $item->update($data);

        return $result ? $item->fresh() : null;
    }

    public function delete(ProcessStep $item): bool
    {
        return $item->delete();
    }
}
