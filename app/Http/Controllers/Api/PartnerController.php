<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Partner\PartnerBenefitResource;
use App\Http\Resources\Partner\PartnerStepResource;
use App\Http\Resources\Partner\PartnerVariantResource;
use App\Services\Partner\PartnerService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;

final class PartnerController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly PartnerService $service,
    ) {}

    public function variants(): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerVariantResource::collection($this->service->getVariants()),
            successMessageKey: 'partner.variants'
        );
    }

    public function steps(): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerStepResource::collection($this->service->getSteps()),
            successMessageKey: 'partner.steps'
        );
    }

    public function benefits(): JsonResponse
    {
        return $this->executeAction(
            action: fn() => PartnerBenefitResource::collection($this->service->getBenefits()),
            successMessageKey: 'partner.benefits'
        );
    }
}
