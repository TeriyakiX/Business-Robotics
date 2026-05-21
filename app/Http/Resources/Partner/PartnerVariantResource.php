<?php

declare(strict_types=1);

namespace App\Http\Resources\Partner;

use App\Http\Resources\BaseResource;
use App\Models\PartnerVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PartnerVariant
 */
final class PartnerVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            PartnerVariant::ID => $this->{PartnerVariant::ID},
            PartnerVariant::TYPE => $this->{PartnerVariant::TYPE}?->value,
            PartnerVariant::TITLE => $this->{PartnerVariant::TITLE},
            PartnerVariant::DESCRIPTION => $this->{PartnerVariant::DESCRIPTION},
            PartnerVariant::PERCENTAGE => $this->{PartnerVariant::PERCENTAGE},
            PartnerVariant::MIN_AMOUNT => $this->{PartnerVariant::MIN_AMOUNT},
            PartnerVariant::AMOUNT_LABEL => $this->{PartnerVariant::AMOUNT_LABEL},
            PartnerVariant::BADGE_COLOR => $this->{PartnerVariant::BADGE_COLOR},
            PartnerVariant::BADGE_BG => $this->{PartnerVariant::BADGE_BG},
            PartnerVariant::TAGS => $this->{PartnerVariant::TAGS},

            'type_label' => $this->{PartnerVariant::TYPE}?->label(),
        ];
    }
}
