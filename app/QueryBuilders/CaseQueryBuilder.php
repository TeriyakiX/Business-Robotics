<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enums\Case\CaseIndustryEnum;
use App\Models\BusinessCase;
use Illuminate\Database\Eloquent\Builder;

final class CaseQueryBuilder extends Builder
{
    public function whereVisible(): self
    {
        return $this->where(BusinessCase::IS_VISIBLE, true);
    }

    public function whereHidden(): self
    {
        return $this->where(BusinessCase::IS_VISIBLE, false);
    }

    public function whereIndustry(CaseIndustryEnum $industry): self
    {
        return $this->where(BusinessCase::INDUSTRY, $industry->value);
    }

    public function orderBySortOrder(string $direction = 'asc'): self
    {
        return $this->orderBy(BusinessCase::SORT_ORDER, $direction);
    }

    public function search(string $search): self
    {
        return $this->where(function ($query) use ($search) {
            $query->where(BusinessCase::TITLE, 'like', "%{$search}%")
                ->orWhere(BusinessCase::CLIENT_NAME, 'like', "%{$search}%")
                ->orWhere(BusinessCase::DESCRIPTION, 'like', "%{$search}%");
        });
    }

    public function withMetrics(): self
    {
        return $this->addSelect(['metrics']);
    }
}
