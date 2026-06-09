<?php

declare(strict_types=1);

namespace App\Http\Resources\Article;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ArticleListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'category' => $this->categoryRelation?->name,
            'category_slug' => $this->categoryRelation?->slug,
            'category_color' => $this->categoryRelation?->color ?? '#00CFFF',
            'category_bg_color' => $this->categoryRelation?->bg_color ?? 'rgba(0,207,255,0.08)',
            'description' => $this->description,
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
