<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Category extends Model
{
    use HasUuids;

    protected $table = 'categories';

    protected $fillable = [
        'slug',
        'name',
        'color',
        'bg_color',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Связь со статьями
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, Article::CATEGORY);
    }
}
