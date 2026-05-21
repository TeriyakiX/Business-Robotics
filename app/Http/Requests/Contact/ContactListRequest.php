<?php

declare(strict_types=1);

namespace App\Http\Requests\Contact;

use App\DTOs\Contact\ContactListDto;
use Illuminate\Foundation\Http\FormRequest;

final class ContactListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('status') && $this->input('status')) {
            $data['status'] = $this->input('status');
        }

        if ($this->has('search') && $this->input('search')) {
            $data['search'] = $this->input('search');
        }

        if ($this->has('order_by')) {
            $data['order_by'] = $this->input('order_by');
        }

        if ($this->has('order_direction')) {
            $data['order_direction'] = $this->input('order_direction');
        }

        if ($this->has('limit')) {
            $data['limit'] = (int) $this->input('limit');
        }

        if ($this->has('offset')) {
            $data['offset'] = (int) $this->input('offset');
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:new,processed,contacted,rejected',
            'order_by' => 'nullable|string|in:created_at,updated_at,name,phone,status',
            'order_direction' => 'nullable|string|in:asc,desc',
            'limit' => 'nullable|integer|min:1|max:100',
            'offset' => 'nullable|integer|min:0',
        ];
    }

    public function toDto(): ContactListDto
    {
        return new ContactListDto(
            search: $this->input('search'),
            status: $this->input('status'),
            order_by: $this->input('order_by', 'created_at'),
            order_direction: $this->input('order_direction', 'desc'),
            limit: $this->input('limit'),
            offset: $this->input('offset'),
        );
    }
}
