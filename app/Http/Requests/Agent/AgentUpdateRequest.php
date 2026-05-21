<?php

declare(strict_types=1);

namespace App\Http\Requests\Agent;

use App\DTOs\Agent\AgentUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

final class AgentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('is_active')) {
            $isActive = $this->input('is_active');
            if (is_string($isActive)) {
                $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
            }
            $data['is_active'] = $isActive;
        }

        if ($this->has('sort_order') && $this->input('sort_order') !== null) {
            $data['sort_order'] = (int) $this->input('sort_order');
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:500'],
            'icon_name' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function toDto(): AgentUpdateDto
    {
        return new AgentUpdateDto(
            name: $this->input('name'),
            tag: $this->input('tag'),
            description: $this->input('description'),
            features: $this->input('features'),
            icon_name: $this->input('icon_name'),
            sort_order: $this->input('sort_order'),
            is_active: $this->input('is_active'),
        );
    }
}
