<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\Case\CaseCreateDto;
use App\DTOs\Case\CaseListDto;
use App\DTOs\Case\CaseUpdateDto;
use App\Models\BusinessCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final readonly class CaseRepository
{
    public function list(CaseListDto $dto): Collection
    {
        $query = BusinessCase::query()
            ->when($dto->search, fn($q) => $q->search($dto->search))
            ->when($dto->industry, fn($q) => $q->whereIndustry($dto->industry))
            ->when($dto->is_visible !== null, fn($q) => $dto->is_visible ? $q->whereVisible() : $q->whereHidden())
            ->when($dto->order_by === 'sort_order', fn($q) => $q->orderBySortOrder($dto->order_direction ?? 'asc'))
            ->when($dto->order_by && $dto->order_by !== 'sort_order', function ($q) use ($dto) {
                $direction = $dto->order_direction ?? 'desc';
                return $q->orderBy($dto->order_by, $direction);
            })
            ->when($dto->limit, fn($q) => $q->limit($dto->limit))
            ->when($dto->offset, fn($q) => $q->offset($dto->offset));

        return $query->get();
    }

    public function item(string $id): ?BusinessCase
    {
        return BusinessCase::query()->find($id);
    }

    public function create(CaseCreateDto $dto): BusinessCase
    {
        return DB::transaction(function () use ($dto) {
            return BusinessCase::query()->create($dto->toDatabaseArray());
        });
    }

    public function update(BusinessCase $case, CaseUpdateDto $dto): ?BusinessCase
    {
        $data = [];

        if ($dto->title !== null) $data['title'] = $dto->title;
        if ($dto->client_name !== null) $data['client_name'] = $dto->client_name;
        if ($dto->client_role !== null) $data['client_role'] = $dto->client_role;
        if ($dto->client_avatar_initials !== null) $data['client_avatar_initials'] = $dto->client_avatar_initials;
        if ($dto->industry !== null) $data['industry'] = $dto->industry->value;
        if ($dto->metrics !== null) $data['metrics'] = $dto->metrics;
        if ($dto->description !== null) $data['description'] = $dto->description;
        if ($dto->testimonial !== null) $data['testimonial'] = $dto->testimonial;
        if ($dto->sort_order !== null) $data['sort_order'] = $dto->sort_order;
        if ($dto->is_visible !== null) $data['is_visible'] = $dto->is_visible;

        if (empty($data)) {
            return $case;
        }

        $result = $case->update($data);

        return $result ? $case->fresh() : null;
    }

    public function delete(BusinessCase $case): bool
    {
        return $case->delete();
    }

    public function forceDelete(BusinessCase $case): bool
    {
        return $case->forceDelete();
    }

    public function restore(BusinessCase $case): bool
    {
        return $case->restore();
    }
}
