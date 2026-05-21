<?php

declare(strict_types=1);

namespace App\Http\Requests\Contact;

use App\DTOs\Contact\ContactCreateDto;
use Illuminate\Foundation\Http\FormRequest;

final class ContactCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'regex:/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/'],
            'company' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => __('responses.contact.invalid_phone'),
            'name.required' => __('responses.contact.name_required'),
            'phone.required' => __('responses.contact.phone_required'),
        ];
    }

    public function toDto(): ContactCreateDto
    {
        return new ContactCreateDto(
            name: $this->validated('name'),
            phone: $this->validated('phone'),
            company: $this->validated('company'),
        );
    }
}
