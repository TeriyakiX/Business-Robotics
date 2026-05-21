<?php

declare(strict_types=1);

namespace App\Http\Requests\PartnerVariant;

use App\DTOs\PartnerVariant\PartnerVariantUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

final class PartnerVariantUpdateRequest extends FormRequest
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

        if ($this->has('percentage') && $this->input('percentage') !== null) {
            $data['percentage'] = (int) $this->input('percentage');
        }

        if ($this->has('min_amount') && $this->input('min_amount') !== null) {
            $data['min_amount'] = (int) $this->input('min_amount');
        }

        if ($this->has('tags') && $this->input('tags') !== null) {
            $data['tags'] = is_array($this->input('tags')) ? $this->input('tags') : explode(',', $this->input('tags'));
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable|string|in:development,subscription',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'percentage' => 'nullable|integer|min:0|max:100',
            'min_amount' => 'nullable|integer|min:0',
            'amount_label' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:20',
            'badge_bg' => 'nullable|string|max:20',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function toDto(): PartnerVariantUpdateDto
    {
        return new PartnerVariantUpdateDto(
            type: $this->input('type'),
            title: $this->input('title'),
            description: $this->input('description'),
            percentage: $this->input('percentage'),
            min_amount: $this->input('min_amount'),
            amount_label: $this->input('amount_label'),
            badge_color: $this->input('badge_color'),
            badge_bg: $this->input('badge_bg'),
            tags: $this->input('tags'),
            sort_order: $this->input('sort_order'),
            is_active: $this->input('is_active'),
        );
    }
}
