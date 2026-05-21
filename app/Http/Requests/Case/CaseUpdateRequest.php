<?php

declare(strict_types=1);

namespace App\Http\Requests\Case;

use App\DTOs\Case\CaseUpdateDto;
use App\Enums\Case\CaseIndustryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CaseUpdateRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'client_role' => ['nullable', 'string', 'max:255'],
            'client_avatar_initials' => ['nullable', 'string', 'max:10'],
            'industry' => ['nullable', 'string', Rule::in(array_column(CaseIndustryEnum::cases(), 'value'))],
            'metrics' => ['nullable', 'array', 'min:1', 'max:5'],
            'metrics.*.value' => ['required_with:metrics', 'string'],
            'metrics.*.label' => ['required_with:metrics', 'string'],
            'description' => ['nullable', 'string'],
            'testimonial' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }

    public function toDto(): CaseUpdateDto
    {
        $industry = $this->input('industry');

        return new CaseUpdateDto(
            title: $this->input('title'),
            client_name: $this->input('client_name'),
            client_role: $this->input('client_role'),
            client_avatar_initials: $this->input('client_avatar_initials'),
            industry: $industry ? CaseIndustryEnum::tryFrom($industry) : null,
            metrics: $this->input('metrics'),
            description: $this->input('description'),
            testimonial: $this->input('testimonial'),
            sort_order: $this->input('sort_order'),
            is_visible: $this->input('is_visible'),
        );
    }
}
