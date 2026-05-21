<?php

declare(strict_types=1);

namespace App\Http\Resources\PartnerVariant;

use App\Models\PartnerVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PartnerVariant
 */
final class PartnerVariantFullResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type?->value,
            'type_label' => $this->type?->label(),
            'title' => $this->title,
            'description' => $this->description,
            'percentage' => $this->percentage,
            'min_amount' => $this->min_amount,
            'amount_label' => $this->amount_label,
            'badge_color' => $this->badge_color,
            'badge_bg' => $this->badge_bg,
            'tags' => $this->tags,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
