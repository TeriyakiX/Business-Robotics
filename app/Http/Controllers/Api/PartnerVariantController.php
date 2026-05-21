<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerVariant\PartnerVariantCreateRequest;
use App\Http\Requests\PartnerVariant\PartnerVariantListRequest;
use App\Http\Requests\PartnerVariant\PartnerVariantUpdateRequest;
use App\Http\Resources\PartnerVariant\PartnerVariantFullResource;
use App\Http\Resources\PartnerVariant\PartnerVariantListResource;
use App\Services\PartnerVariant\PartnerVariantService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PartnerVariantController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly PartnerVariantService $service,
    ) {}


    public function index(PartnerVariantListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerVariantListResource::collection(
                $this->service->list(
                    type: $request->input('type'),
                    onlyActive: true,
                    search: null,
                    isActive: null
                )
            ),
            successMessageKey: 'partner.variants'
        );
    }


    public function list(PartnerVariantListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerVariantListResource::collection(
                $this->service->list(
                    type: $request->input('type'),
                    onlyActive: $request->input('only_active', false),
                    search: $request->input('search'),
                    isActive: $request->input('is_active')
                )
            ),
            successMessageKey: 'partner.variants'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerVariantFullResource($this->service->item($id)),
            successMessageKey: 'partner.variant.item'
        );
    }

    public function create(PartnerVariantCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerVariantFullResource($this->service->create($request->toDto())),
            successMessageKey: 'partner.variant.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, PartnerVariantUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerVariantFullResource($this->service->update($id, $request->toDto())),
            successMessageKey: 'partner.variant.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'partner.variant.delete'
        );
    }
}
