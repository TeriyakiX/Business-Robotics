<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\Agent\AgentCreateDto;
use App\DTOs\Agent\AgentListDto;
use App\DTOs\Agent\AgentUpdateDto;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final readonly class AgentRepository
{
    public function list(AgentListDto $dto): Collection
    {
        $query = Agent::query();

        if ($dto->search) {
            $query->search($dto->search);
        }

        if ($dto->is_active !== null) {
            if ($dto->is_active) {
                $query->whereActive();
            } else {
                $query->whereInactive();
            }
        }

        if ($dto->order_by === 'sort_order') {
            $query->orderBySortOrder($dto->order_direction ?? 'asc');
        } elseif ($dto->order_by && $dto->order_by !== 'sort_order') {
            $direction = $dto->order_direction ?? 'desc';
            $query->orderBy($dto->order_by, $direction);
        } else {
            $query->orderBySortOrder('asc');
        }

        if ($dto->limit) {
            $query->limit($dto->limit);
        }
        if ($dto->offset) {
            $query->offset($dto->offset);
        }

        return $query->get();
    }

    public function item(string $id): ?Agent
    {
        return Agent::query()->find($id);
    }

    public function create(AgentCreateDto $dto): Agent
    {
        return DB::transaction(function () use ($dto) {
            return Agent::query()->create($dto->toDatabaseArray());
        });
    }

    public function update(Agent $agent, AgentUpdateDto $dto): ?Agent
    {
        $result = $agent->update($dto->toDatabaseArray());
        return $result ? $agent->fresh() : null;
    }

    public function delete(Agent $agent): bool
    {
        return $agent->delete();
    }

    public function forceDelete(Agent $agent): bool
    {
        return $agent->forceDelete();
    }

    public function restore(Agent $agent): bool
    {
        return $agent->restore();
    }
}
