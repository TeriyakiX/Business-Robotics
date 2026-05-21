<?php

declare(strict_types=1);

namespace App\Http\Requests\ProcessStep;

use App\DTOs\ProcessStep\ProcessStepCreateDto;
use Illuminate\Foundation\Http\FormRequest;

final class ProcessStepCreateRequest extends FormRequest
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
            'day_range' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function toDto(): ProcessStepCreateDto
    {
        return new ProcessStepCreateDto(
            number: $this->input('number'),
            title: $this->input('title'),
            description: $this->input('description'),
            day_range: $this->input('day_range'),
            sort_order: $this->input('sort_order', 0),
            is_active: $this->input('is_active', true),
        );
    }
}
