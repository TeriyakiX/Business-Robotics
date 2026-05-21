<?php

declare(strict_types=1);

namespace App\Http\Resources\Article;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ArticleFullResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'category' => $this->category?->value,
            'category_label' => $this->category?->label(),
            'category_color' => $this->category_color,
            'category_bg_color' => $this->category_bg_color,
            'description' => $this->description,
            'content' => $this->content,
            'reading_time' => $this->reading_time,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'views_count' => $this->views_count,
            'is_published' => $this->is_published,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'cover_url' => $this->cover_url,
            'gallery_urls' => $this->gallery_urls,
        ];
    }
}
