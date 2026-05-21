<?php

declare(strict_types=1);

namespace App\Http\Requests\Case;

use App\DTOs\Case\CaseListDto;
use App\Enums\Case\CaseIndustryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CaseListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('is_visible') && $this->input('is_visible') !== null && $this->input('is_visible') !== '') {
            $isVisible = $this->input('is_visible');
            if (is_string($isVisible)) {
                $isVisible = filter_var($isVisible, FILTER_VALIDATE_BOOLEAN);
            }
            $data['is_visible'] = $isVisible;
        }

        $industry = $this->input('industry');
        if ($industry && $industry !== '') {
            $industryMapping = [
                'medical' => 'medicine',
                'fitness' => 'fitness',
                'legal' => 'legal',
                'realty' => 'real_estate',
                'auto' => null,
            ];

            if (isset($industryMapping[$industry])) {
                $data['industry'] = $industryMapping[$industry];
            } else {
                $data['industry'] = $industry;
            }
        }

        if ($this->has('limit') && $this->input('limit') !== null && $this->input('limit') !== '') {
            $data['limit'] = (int) $this->input('limit');
        }

        if ($this->has('offset') && $this->input('offset') !== null && $this->input('offset') !== '') {
            $data['offset'] = (int) $this->input('offset');
        }

        if ($this->has('per_page') && $this->input('per_page') !== null && $this->input('per_page') !== '') {
            $data['per_page'] = (int) $this->input('per_page');
        }

        if ($this->has('page') && $this->input('page') !== null && $this->input('page') !== '') {
            $data['page'] = (int) $this->input('page');
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', Rule::in(array_column(CaseIndustryEnum::cases(), 'value'))],
            'is_visible' => ['nullable', 'boolean'],
            'order_by' => ['nullable', 'string', Rule::in(['title', 'sort_order', 'created_at'])],
            'order_direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function toDto(): CaseListDto
    {
        $industry = $this->input('industry');

        return new CaseListDto(
            search: $this->input('search'),
            industry: $industry && $industry !== '' ? CaseIndustryEnum::tryFrom($industry) : null,
            is_visible: $this->input('is_visible', true),
            order_by: $this->input('order_by', 'sort_order'),
            order_direction: $this->input('order_direction', 'asc'),
            limit: $this->input('limit'),
            offset: $this->input('offset'),
            per_page: $this->input('per_page'),
            page: $this->input('page'),
        );
    }
}
