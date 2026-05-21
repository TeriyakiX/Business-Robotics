<?php

declare(strict_types=1);

namespace App\Http\Requests\PartnerVariant;

use App\DTOs\PartnerVariant\PartnerVariantCreateDto;
use Illuminate\Foundation\Http\FormRequest;

final class PartnerVariantCreateRequest extends FormRequest
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
            'percentage' => (int) $this->input('percentage'),
            'min_amount' => (int) $this->input('min_amount'),
            'tags' => $this->input('tags') ? (is_array($this->input('tags')) ? $this->input('tags') : explode(',', $this->input('tags'))) : [],
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:development,subscription',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'percentage' => 'required|integer|min:0|max:100',
            'min_amount' => 'required|integer|min:0',
            'amount_label' => 'required|string|max:255',
            'badge_color' => 'nullable|string|max:20',
            'badge_bg' => 'nullable|string|max:20',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function toDto(): PartnerVariantCreateDto
    {
        return new PartnerVariantCreateDto(
            type: $this->input('type'),
            title: $this->input('title'),
            description: $this->input('description'),
            percentage: $this->input('percentage'),
            min_amount: $this->input('min_amount'),
            amount_label: $this->input('amount_label'),
            badge_color: $this->input('badge_color'),
            badge_bg: $this->input('badge_bg'),
            tags: $this->input('tags', []),
            sort_order: $this->input('sort_order', 0),
            is_active: $this->input('is_active', true),
        );
    }
}
