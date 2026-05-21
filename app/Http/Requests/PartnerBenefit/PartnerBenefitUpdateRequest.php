<?php

declare(strict_types=1);

namespace App\Http\Requests\PartnerBenefit;

use App\DTOs\PartnerBenefit\PartnerBenefitUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

final class PartnerBenefitUpdateRequest extends FormRequest
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

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon_name' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function toDto(): PartnerBenefitUpdateDto
    {
        return new PartnerBenefitUpdateDto(
            title: $this->input('title'),
            description: $this->input('description'),
            icon_name: $this->input('icon_name'),
            sort_order: $this->input('sort_order'),
            is_active: $this->input('is_active'),
        );
    }
}
