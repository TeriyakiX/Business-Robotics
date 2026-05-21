<?php

declare(strict_types=1);

namespace App\Services\PartnerVariant;

use App\DTOs\PartnerVariant\PartnerVariantCreateDto;
use App\DTOs\PartnerVariant\PartnerVariantUpdateDto;
use App\Exceptions\PartnerVariant\PartnerVariantNotFoundException;
use App\Models\PartnerVariant;
use App\Repositories\PartnerVariantRepository;
use Illuminate\Database\Eloquent\Collection;

final readonly class PartnerVariantService
{
    public function __construct(
        private PartnerVariantRepository $repository,
    ) {}

    public function list(?string $type = null, bool $onlyActive = false, ?string $search = null, ?bool $isActive = null): Collection
    {
        return $this->repository->getAll($type, $onlyActive, $search, $isActive);
    }

    /**
     * @throws PartnerVariantNotFoundException
     */
    public function item(string $id): PartnerVariant
    {
        $item = $this->repository->findById($id);

        if (!$item) {
            throw new PartnerVariantNotFoundException();
        }

        return $item;
    }

    public function create(PartnerVariantCreateDto $dto): PartnerVariant
    {
        return $this->repository->create($dto);
    }

    /**
     * @throws PartnerVariantNotFoundException
     */
    public function update(string $id, PartnerVariantUpdateDto $dto): PartnerVariant
    {
        $item = $this->item($id);
        $updated = $this->repository->update($item, $dto);

        if (!$updated) {
            throw new PartnerVariantNotFoundException();
        }

        return $updated;
    }

    /**
     * @throws PartnerVariantNotFoundException
     */
    public function delete(string $id): bool
    {
        $item = $this->item($id);

        return $this->repository->delete($item);
    }
}
