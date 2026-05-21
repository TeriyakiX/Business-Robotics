<?php

declare(strict_types=1);

namespace App\Services\MarqueeItem;

use App\DTOs\MarqueeItem\MarqueeItemCreateDto;
use App\DTOs\MarqueeItem\MarqueeItemUpdateDto;
use App\Exceptions\MarqueeItem\MarqueeItemNotFoundException;
use App\Models\MarqueeItem;
use App\Repositories\MarqueeItemRepository;
use Illuminate\Database\Eloquent\Collection;

final readonly class MarqueeItemService
{
    public function __construct(
        private MarqueeItemRepository $repository,
    ) {}

    public function list(bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        return $this->repository->getAll($onlyActive, $search, $isActive);
    }

    /**
     * @throws MarqueeItemNotFoundException
     */
    public function item(string $id): MarqueeItem
    {
        $item = $this->repository->findById($id);

        if (!$item) {
            throw new MarqueeItemNotFoundException();
        }

        return $item;
    }

    public function create(MarqueeItemCreateDto $dto): MarqueeItem
    {
        return $this->repository->create($dto);
    }

    /**
     * @throws MarqueeItemNotFoundException
     */
    public function update(string $id, MarqueeItemUpdateDto $dto): MarqueeItem
    {
        $item = $this->item($id);
        $updated = $this->repository->update($item, $dto);

        if (!$updated) {
            throw new MarqueeItemNotFoundException();
        }

        return $updated;
    }

    /**
     * @throws MarqueeItemNotFoundException
     */
    public function delete(string $id): bool
    {
        $item = $this->item($id);

        return $this->repository->delete($item);
    }
}
