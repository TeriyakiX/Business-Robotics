<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\DTOs\Settings\SettingsUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

final class SettingsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_title_line_1' => 'nullable|string|max:255',
            'hero_title_line_2' => 'nullable|string|max:255',
            'hero_title_line_3' => 'nullable|string|max:255',
            'hero_eyebrow' => 'nullable|string|max:255',
            'hero_button_text' => 'nullable|string|max:255',
            'hero_use_spline' => 'nullable|string',
            'hero_top_text' => 'nullable|string',
            'cta_pill' => 'nullable|string',
            'cta_title' => 'nullable|string',
            'cta_subtitle' => 'nullable|string',
            'cta_button_text' => 'nullable|string',
            'cta_button_telegram' => 'nullable|string',
            'cta_note' => 'nullable|string',
            'contact_form_pill' => 'nullable|string',
            'contact_form_title' => 'nullable|string',
            'contact_form_subtitle' => 'nullable|string',
            'contact_form_name_label' => 'nullable|string',
            'contact_form_phone_label' => 'nullable|string',
            'contact_form_company_label' => 'nullable|string',
            'contact_form_submit_text' => 'nullable|string',
            'contact_form_success_title' => 'nullable|string',
            'contact_form_success_message' => 'nullable|string',
            'contact_form_privacy_note' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_address' => 'nullable|string',
            'footer_brand_name' => 'nullable|string',
            'footer_brand_desc' => 'nullable|string',
            'footer_products_title' => 'nullable|string',
            'footer_company_title' => 'nullable|string',
            'footer_contacts_title' => 'nullable|string',
            'footer_phone' => 'nullable|string',
            'footer_email' => 'nullable|email',
            'footer_telegram' => 'nullable|url',
            'footer_copyright' => 'nullable|string',
        ];
    }

    public function toDto(): SettingsUpdateDto
    {
        return SettingsUpdateDto::fromArray($this->validated());
    }
}
