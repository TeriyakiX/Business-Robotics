<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\MarqueeItem\MarqueeItemCreateDto;
use App\DTOs\MarqueeItem\MarqueeItemUpdateDto;
use App\Models\MarqueeItem;
use Illuminate\Database\Eloquent\Collection;

final readonly class MarqueeItemRepository
{
    public function getAll(bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        $query = MarqueeItem::query();

        if ($onlyActive) {
            $query->active();
        }

        if ($isActive !== null) {
            $query->where(MarqueeItem::IS_ACTIVE, $isActive);
        }

        if ($search) {
            $query->where(MarqueeItem::NAME, 'like', "%{$search}%");
        }

        $query->ordered();

        return $query->get();
    }

    public function findById(string $id): ?MarqueeItem
    {
        return MarqueeItem::query()->find($id);
    }

    public function create(MarqueeItemCreateDto $dto): MarqueeItem
    {
        return MarqueeItem::query()->create($dto->toArray());
    }

    public function update(MarqueeItem $item, MarqueeItemUpdateDto $dto): ?MarqueeItem
    {
        $data = array_filter($dto->toArray(), fn($value) => $value !== null);

        if (empty($data)) {
            return $item;
        }

        $result = $item->update($data);

        return $result ? $item->fresh() : null;
    }

    public function delete(MarqueeItem $item): bool
    {
        return $item->delete();
    }
}
