<?php

declare(strict_types=1);

namespace App\Services\Partner;

use App\Repositories\PartnerRepository;
use Illuminate\Database\Eloquent\Collection;

final readonly class PartnerService
{
    public function __construct(
        private PartnerRepository $repository,
    ) {}

    public function getVariants(bool $onlyActive = true): Collection
    {
        return $this->repository->getVariants($onlyActive);
    }

    public function getSteps(bool $onlyActive = true): Collection
    {
        return $this->repository->getSteps($onlyActive);
    }

    public function getBenefits(bool $onlyActive = true): Collection
    {
        return $this->repository->getBenefits($onlyActive);
    }
}
