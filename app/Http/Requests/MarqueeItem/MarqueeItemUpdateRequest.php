<?php

declare(strict_types=1);

namespace App\Http\Requests\MarqueeItem;

use App\DTOs\MarqueeItem\MarqueeItemUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

final class MarqueeItemUpdateRequest extends FormRequest
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

        if ($this->has('name')) {
            $data['name'] = $this->input('name');
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'is_active.boolean' => 'Поле is_active должно быть true или false (получено: ' . $this->input('is_active') . ')',
        ];
    }

    public function toDto(): MarqueeItemUpdateDto
    {
        return new MarqueeItemUpdateDto(
            name: $this->input('name'),
            sort_order: $this->input('sort_order'),
            is_active: $this->input('is_active'),
        );
    }
}
