<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessStep\ProcessStepCreateRequest;
use App\Http\Requests\ProcessStep\ProcessStepListRequest;
use App\Http\Requests\ProcessStep\ProcessStepUpdateRequest;
use App\Http\Resources\ProcessStep\ProcessStepFullResource;
use App\Http\Resources\ProcessStep\ProcessStepListResource;
use App\Services\ProcessStep\ProcessStepService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProcessStepController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly ProcessStepService $service,
    ) {}


    public function index(ProcessStepListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => ProcessStepListResource::collection(
                $this->service->list(
                    onlyActive: true,
                    search: null,
                    isActive: null
                )
            ),
            successMessageKey: 'process_step.list'
        );
    }


    public function list(ProcessStepListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => ProcessStepListResource::collection(
                $this->service->list(
                    onlyActive: $request->input('only_active', false),
                    search: $request->input('search'),
                    isActive: $request->input('is_active')
                )
            ),
            successMessageKey: 'process_step.list'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new ProcessStepFullResource($this->service->item($id)),
            successMessageKey: 'process_step.item'
        );
    }

    public function create(ProcessStepCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new ProcessStepFullResource($this->service->create($request->toDto())),
            successMessageKey: 'process_step.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, ProcessStepUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new ProcessStepFullResource($this->service->update($id, $request->toDto())),
            successMessageKey: 'process_step.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'process_step.delete'
        );
    }
}
