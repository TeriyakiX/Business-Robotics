<?php

declare(strict_types=1);

namespace App\Http\Requests\Agent;

use App\DTOs\Agent\AgentListDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AgentListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('is_active') && $this->input('is_active') !== null && $this->input('is_active') !== '') {
            $isActive = $this->input('is_active');
            if (is_string($isActive)) {
                $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
            }
            $data['is_active'] = $isActive;
        }

        if ($this->has('limit')) {
            $data['limit'] = (int) $this->input('limit');
        }

        if ($this->has('offset')) {
            $data['offset'] = (int) $this->input('offset');
        }

        if ($this->has('per_page')) {
            $data['per_page'] = (int) $this->input('per_page');
        }

        if ($this->has('page')) {
            $data['page'] = (int) $this->input('page');
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'order_by' => ['nullable', 'string', Rule::in(['name', 'sort_order', 'created_at'])],
            'order_direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'cursor' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'with_features' => ['nullable', 'boolean'],
        ];
    }

    public function toDto(): AgentListDto
    {
        return new AgentListDto(
            search: $this->input('search'),
            is_active: $this->input('is_active'),
            order_by: $this->input('order_by', 'sort_order'),
            order_direction: $this->input('order_direction', 'asc'),
            limit: $this->input('limit'),
            offset: $this->input('offset'),
            per_page: $this->input('per_page'),
            cursor: $this->input('cursor'),
            page: $this->input('page'),
            with_features: $this->input('with_features', true),
        );
    }
}
