<?php

declare(strict_types=1);

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

final class ContactUpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('status')) {
            $data['status'] = $this->input('status');
        }

        if ($this->has('notes')) {
            $data['notes'] = $this->input('notes');
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:new,processed,contacted,rejected',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
