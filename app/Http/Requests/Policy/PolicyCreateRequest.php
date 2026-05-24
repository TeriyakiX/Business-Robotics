<?php

use App\DTOs\Policy\PolicyCreateDto;
use Illuminate\Foundation\Http\FormRequest;

final class PolicyCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:policies,slug'],
            'content' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function toDto(): PolicyCreateDto
    {
        return new PolicyCreateDto(
            title: $this->input('title'),
            slug: $this->input('slug'),
            content: $this->input('content'),
            is_active: $this->input('is_active', true),
        );
    }
}
