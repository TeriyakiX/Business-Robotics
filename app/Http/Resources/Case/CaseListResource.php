<?php

declare(strict_types=1);

namespace App\Http\Resources\Case;

use App\Models\BusinessCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BusinessCase
 */
final class CaseListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'client_name' => $this->client_name,
            'client_role' => $this->client_role,
            'client_avatar_initials' => $this->client_avatar_initials,
            'industry' => $this->industry?->value,
            'industry_label' => $this->industry?->label(),
            'metrics' => $this->metrics,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'is_visible' => $this->is_visible,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
