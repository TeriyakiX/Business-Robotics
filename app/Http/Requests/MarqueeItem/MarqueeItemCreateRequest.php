<?php

declare(strict_types=1);

namespace App\Http\Requests\MarqueeItem;

use App\DTOs\MarqueeItem\MarqueeItemCreateDto;
use Illuminate\Foundation\Http\FormRequest;

final class MarqueeItemCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $isActive = $this->input('is_active');

        if (is_string($isActive)) {
            $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
        }

        $this->merge([
            'is_active' => $isActive,
            'sort_order' => $this->input('sort_order') !== null ? (int) $this->input('sort_order') : 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
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

    public function toDto(): MarqueeItemCreateDto
    {
        return new MarqueeItemCreateDto(
            name: $this->input('name'),
            sort_order: (int) $this->input('sort_order', 0),
            is_active: (bool) $this->input('is_active', true),
        );
    }
}
