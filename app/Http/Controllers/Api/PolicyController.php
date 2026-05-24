<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Services\Policy\PolicyService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;

final class PolicyController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly PolicyService $service,
    ) {}

    // PUBLIC
    public function list(): JsonResponse
    {
        return $this->executeAction(
            action: fn() => $this->service->list(),
            successMessageKey: 'policy.list'
        );
    }

    public function show(string $slug): JsonResponse
    {
        return $this->executeAction(
            action: fn() => $this->service->showBySlug($slug),
            successMessageKey: 'policy.item'
        );
    }

    // ADMIN
    public function adminList(): JsonResponse
    {
        return $this->executeAction(
            action: fn() => $this->service->list(),
            successMessageKey: 'policy.list'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => $this->service->item($id),
            successMessageKey: 'policy.item'
        );
    }

    public function create(PolicyCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => $this->service->create($request->toDto()),
            successMessageKey: 'policy.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, PolicyUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => $this->service->update($id, $request->toDto()),
            successMessageKey: 'policy.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'policy.delete'
        );
    }

    public function restore(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->restore($id),
            successMessageKey: 'policy.restore'
        );
    }
}
