<?php

declare(strict_types=1);

namespace App\Http\Requests\Article;

use App\DTOs\Article\ArticleCreateDto;
use App\Enums\Article\ArticleCategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

final class ArticleCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('title')) $data['title'] = $this->input('title');
        if ($this->has('slug')) $data['slug'] = $this->input('slug');
        if ($this->has('category')) $data['category'] = $this->input('category');
        if ($this->has('category_color')) $data['category_color'] = $this->input('category_color');
        if ($this->has('category_bg_color')) $data['category_bg_color'] = $this->input('category_bg_color');
        if ($this->has('description')) $data['description'] = $this->input('description');
        if ($this->has('content')) $data['content'] = $this->input('content');
        if ($this->has('reading_time')) $data['reading_time'] = (int) $this->input('reading_time');
        if ($this->has('published_at')) $data['published_at'] = $this->input('published_at');

        if ($this->has('is_published')) {
            $isPublished = $this->input('is_published');
            if (is_string($isPublished)) {
                $isPublished = filter_var($isPublished, FILTER_VALIDATE_BOOLEAN);
            }
            $data['is_published'] = $isPublished;
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug'],
            'title' => ['required', 'string', 'max:500'],
            'category' => ['required', 'string', Rule::in(array_column(ArticleCategoryEnum::cases(), 'value'))],
            'category_color' => ['nullable', 'string', 'max:20'],
            'category_bg_color' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'reading_time' => ['nullable', 'integer', 'min:1', 'max:60'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'file','mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'gallery.*' => ['file', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'gallery' => ['nullable', 'array', 'max:10'],
        ];
    }

    public function toDto(): ArticleCreateDto
    {
        return new ArticleCreateDto(
            slug: $this->input('slug') ?? Str::slug($this->input('title')),
            title: $this->input('title'),
            category: ArticleCategoryEnum::from($this->input('category')),
            category_color: $this->input('category_color'),
            category_bg_color: $this->input('category_bg_color'),
            description: $this->input('description'),
            content: $this->input('content'),
            reading_time: $this->input('reading_time'),
            published_at: $this->input('published_at'),
            is_published: $this->input('is_published', false),
            cover: $this->file('cover'),
            gallery: $this->file('gallery'),
        );
    }
}
