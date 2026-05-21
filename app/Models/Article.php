<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Article\ArticleCategoryEnum;
use App\QueryBuilders\ArticleQueryBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Article extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const string DATABASE_TABLE = 'articles';

    public const string ID = 'id';
    public const string SLUG = 'slug';
    public const string TITLE = 'title';
    public const string CATEGORY = 'category';
    public const string CATEGORY_COLOR = 'category_color';
    public const string CATEGORY_BG_COLOR = 'category_bg_color';
    public const string DESCRIPTION = 'description';
    public const string CONTENT = 'content';
    public const string READING_TIME = 'reading_time';
    public const string PUBLISHED_AT = 'published_at';
    public const string IS_PUBLISHED = 'is_published';
    public const string VIEWS_COUNT = 'views_count';
    public const string COVER_PATH = 'cover_path';
    public const string GALLERY = 'gallery';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';
    public const string DELETED_AT = 'deleted_at';

    protected $table = self::DATABASE_TABLE;

    protected $fillable = [
        self::SLUG,
        self::TITLE,
        self::CATEGORY,
        self::CATEGORY_COLOR,
        self::CATEGORY_BG_COLOR,
        self::DESCRIPTION,
        self::CONTENT,
        self::READING_TIME,
        self::PUBLISHED_AT,
        self::IS_PUBLISHED,
        self::VIEWS_COUNT,
        self::COVER_PATH,
        self::GALLERY,
    ];

    protected $casts = [
        self::CATEGORY => ArticleCategoryEnum::class,
        self::IS_PUBLISHED => 'boolean',
        self::VIEWS_COUNT => 'integer',
        self::PUBLISHED_AT => 'datetime',
        self::GALLERY => 'array',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

    protected $attributes = [
        self::VIEWS_COUNT => 0,
        self::IS_PUBLISHED => false,
    ];

    public function scopePublished($query)
    {
        return $query->where(self::IS_PUBLISHED, true)
            ->where(self::PUBLISHED_AT, '<=', now());
    }

    public function scopeByCategory($query, ArticleCategoryEnum $category)
    {
        return $query->where(self::CATEGORY, $category->value);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy(self::PUBLISHED_AT, 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy(self::VIEWS_COUNT, 'desc');
    }

    public function incrementViews(): void
    {
        $this->increment(self::VIEWS_COUNT);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_path ? asset('storage/' . $this->cover_path) : null;
    }

    public function getGalleryUrlsAttribute(): array
    {
        if (!$this->gallery) return [];

        return collect($this->gallery)
            ->map(fn($path) => asset('storage/' . $path))
            ->toArray();
    }

    public function newEloquentBuilder($query): ArticleQueryBuilder
    {
        return new ArticleQueryBuilder($query);
    }
}
