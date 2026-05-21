<?php

declare(strict_types=1);

namespace App\Http\Requests\PartnerBenefit;

use Illuminate\Foundation\Http\FormRequest;

final class PartnerBenefitListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'only_active' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $isActive = $this->input('is_active');
            if (is_string($isActive)) {
                $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
            }
            $this->merge(['is_active' => $isActive]);
        }
    }
}
