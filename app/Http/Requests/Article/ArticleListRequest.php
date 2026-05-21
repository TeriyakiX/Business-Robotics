<?php

declare(strict_types=1);

namespace App\Http\Requests\Article;

use App\DTOs\Article\ArticleListDto;
use App\Enums\Article\ArticleCategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ArticleListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('is_published') && $this->input('is_published') !== null && $this->input('is_published') !== '') {
            $isPublished = $this->input('is_published');
            if (is_string($isPublished)) {
                $isPublished = filter_var($isPublished, FILTER_VALIDATE_BOOLEAN);
            }
            $data['is_published'] = $isPublished;
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
            'category' => ['nullable', 'string', Rule::in(array_column(ArticleCategoryEnum::cases(), 'value'))],
            'is_published' => ['nullable', 'boolean'],
            'order_by' => ['nullable', 'string', Rule::in(['recent', 'popular', 'title', 'created_at'])],
            'order_direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'cursor' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'with_content' => ['nullable', 'boolean'],
        ];
    }

    public function toDto(): ArticleListDto
    {
        $category = $this->input('category');

        return new ArticleListDto(
            search: $this->input('search'),
            category: $category && $category !== '' ? ArticleCategoryEnum::tryFrom($category) : null,
            is_published: $this->input('is_published'),
            order_by: $this->input('order_by', 'recent'),
            order_direction: $this->input('order_direction', 'desc'),
            limit: $this->input('limit'),
            offset: $this->input('offset'),
            per_page: $this->input('per_page'),
            page: $this->input('page'),
            cursor: $this->input('cursor'),
            with_content: $this->input('with_content', false),
        );
    }
}
