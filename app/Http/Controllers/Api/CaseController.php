<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Case\CaseCreateRequest;
use App\Http\Requests\Case\CaseListRequest;
use App\Http\Requests\Case\CaseUpdateRequest;
use App\Http\Resources\Case\CaseFullResource;
use App\Http\Resources\Case\CaseListResource;
use App\Services\Case\CaseService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CaseController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly CaseService $service,
    ) {}

    public function list(CaseListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => CaseListResource::collection(
                $this->service->list($request->toDto())
            ),
            successMessageKey: 'case.list'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new CaseFullResource($this->service->item($id)),
            successMessageKey: 'case.item'
        );
    }


    public function create(CaseCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new CaseFullResource($this->service->create($request->toDto())),
            successMessageKey: 'case.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, CaseUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new CaseFullResource($this->service->update($id, $request->toDto())),
            successMessageKey: 'case.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'case.delete'
        );
    }

    public function restore(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->restore($id),
            successMessageKey: 'case.restore'
        );
    }
}
