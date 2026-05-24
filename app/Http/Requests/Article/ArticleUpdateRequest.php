<?php

declare(strict_types=1);

namespace App\Http\Requests\Article;

use App\DTOs\Article\ArticleUpdateDto;
use App\Enums\Article\ArticleCategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ArticleUpdateRequest extends FormRequest
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

        if ($this->has('increment_views')) {
            $incrementViews = $this->input('increment_views');
            if (is_string($incrementViews)) {
                $incrementViews = filter_var($incrementViews, FILTER_VALIDATE_BOOLEAN);
            }
            $data['increment_views'] = $incrementViews;
        }

        if ($this->has('delete_cover')) {
            $deleteCover = $this->input('delete_cover');
            if (is_string($deleteCover)) {
                $deleteCover = filter_var($deleteCover, FILTER_VALIDATE_BOOLEAN);
            }
            $data['delete_cover'] = $deleteCover;
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'slug' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', Rule::in(array_column(ArticleCategoryEnum::cases(), 'value'))],
            'category_color' => ['nullable', 'string', 'max:20'],
            'category_bg_color' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'reading_time' => ['nullable', 'integer', 'min:1', 'max:60'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
            'increment_views' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'file','mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'gallery.*' => ['file', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'gallery' => ['nullable', 'array', 'max:10'],
            'delete_cover' => ['nullable', 'boolean'],
        ];
    }

    public function toDto(): ArticleUpdateDto
    {
        $category = $this->input('category');

        return new ArticleUpdateDto(
            slug: $this->input('slug'),
            title: $this->input('title'),
            category: $category && $category !== '' ? ArticleCategoryEnum::tryFrom($category) : null,
            category_color: $this->input('category_color'),
            category_bg_color: $this->input('category_bg_color'),
            description: $this->input('description'),
            content: $this->input('content'),
            reading_time: $this->input('reading_time'),
            published_at: $this->input('published_at'),
            is_published: $this->input('is_published'),
            increment_views: $this->input('increment_views'),
            cover: $this->file('cover'),
            gallery: $this->file('gallery'),
            delete_cover: $this->input('delete_cover', false),
        );
    }
}
