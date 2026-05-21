<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\MarqueeItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MarqueeItem
 */
final class MarqueeItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            MarqueeItem::ID => $this->{MarqueeItem::ID},
            MarqueeItem::NAME => $this->{MarqueeItem::NAME},
        ];
    }
}
