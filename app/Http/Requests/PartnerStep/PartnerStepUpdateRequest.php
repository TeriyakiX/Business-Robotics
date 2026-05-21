<?php

declare(strict_types=1);

namespace App\Http\Requests\PartnerStep;

use App\DTOs\PartnerStep\PartnerStepUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

final class PartnerStepUpdateRequest extends FormRequest
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
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function toDto(): PartnerStepUpdateDto
    {
        return new PartnerStepUpdateDto(
            number: $this->input('number'),
            title: $this->input('title'),
            description: $this->input('description'),
            sort_order: $this->input('sort_order'),
            is_active: $this->input('is_active'),
        );
    }
}
