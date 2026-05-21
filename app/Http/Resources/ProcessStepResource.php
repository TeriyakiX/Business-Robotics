<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ProcessStep;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProcessStep
 */
final class ProcessStepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            ProcessStep::ID => $this->{ProcessStep::ID},
            ProcessStep::NUMBER => $this->{ProcessStep::NUMBER},
            ProcessStep::TITLE => $this->{ProcessStep::TITLE},
            ProcessStep::DESCRIPTION => $this->{ProcessStep::DESCRIPTION},
            ProcessStep::DAY_RANGE => $this->{ProcessStep::DAY_RANGE},
        ];
    }
}
