<?php

declare(strict_types=1);

namespace App\Http\Requests\ProcessStep;

use App\DTOs\ProcessStep\ProcessStepUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

final class ProcessStepUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('is_active')) {
            $data['is_active'] = filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($this->has('sort_order') && $this->input('sort_order') !== null) {
            $data['sort_order'] = (int) $this->input('sort_order');
        }

        if ($this->has('number') && $this->input('number') !== null) {
            $data['number'] = (int) $this->input('number');
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'number' => 'nullable|integer|min:1',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'day_range' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function toDto(): ProcessStepUpdateDto
    {
        return new ProcessStepUpdateDto(
            number: $this->input('number'),
            title: $this->input('title'),
            description: $this->input('description'),
            day_range: $this->input('day_range'),
            sort_order: $this->input('sort_order'),
            is_active: $this->input('is_active'),
        );
    }
}
