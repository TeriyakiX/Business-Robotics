<?php

declare(strict_types=1);

namespace App\Http\Requests\Case;

use App\DTOs\Case\CaseCreateDto;
use App\Enums\Case\CaseIndustryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CaseCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('is_visible')) {
            $isVisible = $this->input('is_visible');
            if (is_string($isVisible)) {
                $isVisible = filter_var($isVisible, FILTER_VALIDATE_BOOLEAN);
            }
            $data['is_visible'] = $isVisible;
        }

        if ($this->has('sort_order') && $this->input('sort_order') !== null) {
            $data['sort_order'] = (int) $this->input('sort_order');
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_role' => ['required', 'string', 'max:255'],
            'client_avatar_initials' => ['nullable', 'string', 'max:10'],
            'industry' => ['required', 'string', Rule::in(array_column(CaseIndustryEnum::cases(), 'value'))],
            'metrics' => ['required', 'array', 'min:1', 'max:5'],
            'metrics.*.value' => ['required', 'string'],
            'metrics.*.label' => ['required', 'string'],
            'description' => ['required', 'string'],
            'testimonial' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }

    public function toDto(): CaseCreateDto
    {
        return new CaseCreateDto(
            title: $this->input('title'),
            client_name: $this->input('client_name'),
            client_role: $this->input('client_role'),
            client_avatar_initials: $this->input('client_avatar_initials'),
            industry: CaseIndustryEnum::from($this->input('industry')),
            metrics: $this->input('metrics'),
            description: $this->input('description'),
            testimonial: $this->input('testimonial'),
            sort_order: $this->input('sort_order', 0),
            is_visible: $this->input('is_visible', true),
        );
    }
}
