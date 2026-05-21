<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Builder;

final class AgentQueryBuilder extends Builder
{
    public function whereActive(): self
    {
        return $this->where(Agent::IS_ACTIVE, true);
    }

    public function whereInactive(): self
    {
        return $this->where(Agent::IS_ACTIVE, false);
    }

    public function orderBySortOrder(string $direction = 'asc'): self
    {
        return $this->orderBy(Agent::SORT_ORDER, $direction);
    }

    public function search(string $search): self
    {
        return $this->where(function ($query) use ($search) {
            $query->where(Agent::NAME, 'like', "%{$search}%")
                ->orWhere(Agent::TAG, 'like', "%{$search}%")
                ->orWhere(Agent::DESCRIPTION, 'like', "%{$search}%");
        });
    }

    public function withFeatures(): self
    {
        return $this->addSelect(['*']);
    }
}
