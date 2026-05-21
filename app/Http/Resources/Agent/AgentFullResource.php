<?php

declare(strict_types=1);

namespace App\Http\Resources\Agent;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Agent
 */
final class AgentFullResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tag' => $this->tag,
            'description' => $this->description,
            'features' => $this->features,
            'icon_name' => $this->icon_name,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
