<?php

declare(strict_types=1);

namespace App\Http\Requests\PartnerStep;

use App\DTOs\PartnerStep\PartnerStepCreateDto;
use Illuminate\Foundation\Http\FormRequest;

final class PartnerStepCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN) : true,
            'sort_order' => $this->input('sort_order') !== null ? (int) $this->input('sort_order') : 0,
            'number' => (int) $this->input('number'),
        ]);
    }

    public function rules(): array
    {
        return [
            'number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function toDto(): PartnerStepCreateDto
    {
        return new PartnerStepCreateDto(
            number: $this->input('number'),
            title: $this->input('title'),
            description: $this->input('description'),
            sort_order: $this->input('sort_order', 0),
            is_active: $this->input('is_active', true),
        );
    }
}
