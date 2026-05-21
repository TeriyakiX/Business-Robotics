<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerBenefit\PartnerBenefitCreateRequest;
use App\Http\Requests\PartnerBenefit\PartnerBenefitListRequest;
use App\Http\Requests\PartnerBenefit\PartnerBenefitUpdateRequest;
use App\Http\Resources\PartnerBenefit\PartnerBenefitFullResource;
use App\Http\Resources\PartnerBenefit\PartnerBenefitListResource;
use App\Services\PartnerBenefit\PartnerBenefitService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PartnerBenefitController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly PartnerBenefitService $service,
    ) {}


    public function index(PartnerBenefitListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerBenefitListResource::collection(
                $this->service->list(
                    onlyActive: true,
                    search: null,
                    isActive: null
                )
            ),
            successMessageKey: 'partner.benefits'
        );
    }


    public function list(PartnerBenefitListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerBenefitListResource::collection(
                $this->service->list(
                    onlyActive: $request->input('only_active', false),
                    search: $request->input('search'),
                    isActive: $request->input('is_active')
                )
            ),
            successMessageKey: 'partner.benefits'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerBenefitFullResource($this->service->item($id)),
            successMessageKey: 'partner.benefit.item'
        );
    }

    public function create(PartnerBenefitCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerBenefitFullResource($this->service->create($request->toDto())),
            successMessageKey: 'partner.benefit.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, PartnerBenefitUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new PartnerBenefitFullResource($this->service->update($id, $request->toDto())),
            successMessageKey: 'partner.benefit.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'partner.benefit.delete'
        );
    }
}
