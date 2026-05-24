<?php

namespace App\Http\Requests\Policy;

use App\DTOs\Policy\PolicyCreateDto;
use App\DTOs\Policy\PolicyUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

final class PolicyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function toDto(): PolicyUpdateDto
    {
        return new PolicyUpdateDto(
            title: $this->input('title'),
            slug: $this->input('slug'),
            content: $this->input('content'),
            is_active: $this->input('is_active'),
        );
    }
}
