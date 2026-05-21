<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerStep\PartnerStepCreateRequest;
use App\Http\Requests\PartnerStep\PartnerStepListRequest;
use App\Http\Requests\PartnerStep\PartnerStepUpdateRequest;
use App\Http\Resources\PartnerStep\PartnerStepFullResource;
use App\Http\Resources\PartnerStep\PartnerStepListResource;
use App\Services\PartnerStep\PartnerStepService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PartnerStepController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly PartnerStepService $service,
    ) {}


    public function index(PartnerStepListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerStepListResource::collection(
                $this->service->list(
                    onlyActive: true,
                    search: null,
                    isActive: null
                )
            ),
            successMessageKey: 'partner.steps'
        );
    }


    public function list(PartnerStepListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerStepListResource::collection(
                $this->service->list(
                    onlyActive: $request->input('only_active', false),
                    search: $request->input('search'),
                    isActive: $request->input('is_active')
                )
            ),
            successMessageKey: 'partner.steps'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerStepFullResource($this->service->item($id)),
            successMessageKey: 'partner.step.item'
        );
    }

    public function create(PartnerStepCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerStepFullResource($this->service->create($request->toDto())),
            successMessageKey: 'partner.step.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, PartnerStepUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerStepFullResource($this->service->update($id, $request->toDto())),
            successMessageKey: 'partner.step.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'partner.step.delete'
        );
    }
}
