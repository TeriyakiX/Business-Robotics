<?php

declare(strict_types=1);

namespace App\Http\Requests\PartnerBenefit;

use App\DTOs\PartnerBenefit\PartnerBenefitCreateDto;
use Illuminate\Foundation\Http\FormRequest;

final class PartnerBenefitCreateRequest extends FormRequest
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
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon_name' => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function toDto(): PartnerBenefitCreateDto
    {
        return new PartnerBenefitCreateDto(
            title: $this->input('title'),
            description: $this->input('description'),
            icon_name: $this->input('icon_name'),
            sort_order: $this->input('sort_order', 0),
            is_active: $this->input('is_active', true),
        );
    }
}
