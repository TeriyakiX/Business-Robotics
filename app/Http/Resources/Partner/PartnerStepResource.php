<?php

declare(strict_types=1);

namespace App\Http\Resources\Partner;

use App\Http\Resources\BaseResource;
use App\Models\PartnerStep;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PartnerStep
 */
final class PartnerStepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            PartnerStep::ID => $this->{PartnerStep::ID},
            PartnerStep::NUMBER => $this->{PartnerStep::NUMBER},
            PartnerStep::TITLE => $this->{PartnerStep::TITLE},
            PartnerStep::DESCRIPTION => $this->{PartnerStep::DESCRIPTION},
        ];
    }
}
