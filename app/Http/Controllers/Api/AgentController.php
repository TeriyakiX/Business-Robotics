<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\AgentCreateRequest;
use App\Http\Requests\Agent\AgentListRequest;
use App\Http\Requests\Agent\AgentUpdateRequest;
use App\Http\Resources\Agent\AgentFullResource;
use App\Http\Resources\Agent\AgentListResource;
use App\Services\Agent\AgentService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AgentController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly AgentService $service,
    ) {}

    public function list(AgentListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => AgentListResource::collection(
                $this->service->list($request->toDto())
            ),
            successMessageKey: 'agent.list'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new AgentFullResource($this->service->item($id)),
            successMessageKey: 'agent.item'
        );
    }

    public function create(AgentCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new AgentFullResource($this->service->create($request->toDto())),
            successMessageKey: 'agent.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, AgentUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new AgentFullResource($this->service->update($id, $request->toDto())),
            successMessageKey: 'agent.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'agent.delete'
        );
    }

    public function restore(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->restore($id),
            successMessageKey: 'agent.restore'
        );
    }
}
