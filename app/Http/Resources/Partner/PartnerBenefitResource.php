<?php

declare(strict_types=1);

namespace App\Http\Resources\Partner;

use App\Http\Resources\BaseResource;
use App\Models\PartnerBenefit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PartnerBenefit
 */
final class PartnerBenefitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            PartnerBenefit::ID => $this->{PartnerBenefit::ID},
            PartnerBenefit::TITLE => $this->{PartnerBenefit::TITLE},
            PartnerBenefit::DESCRIPTION => $this->{PartnerBenefit::DESCRIPTION},
            PartnerBenefit::ICON_NAME => $this->{PartnerBenefit::ICON_NAME}?->value,

            'icon_path' => $this->{PartnerBenefit::ICON_NAME}?->svgPath(),
        ];
    }
}
