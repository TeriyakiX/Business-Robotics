<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Case\CaseIndustryEnum;
use App\Enums\Case\CaseStatusEnum;
use App\QueryBuilders\CaseQueryBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class BusinessCase extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const string DATABASE_TABLE = 'cases';

    public const string ID = 'id';
    public const string TITLE = 'title';
    public const string CLIENT_NAME = 'client_name';
    public const string CLIENT_ROLE = 'client_role';
    public const string CLIENT_AVATAR_INITIALS = 'client_avatar_initials';
    public const string INDUSTRY = 'industry';
    public const string METRICS = 'metrics';
    public const string DESCRIPTION = 'description';
    public const string TESTIMONIAL = 'testimonial';
    public const string SORT_ORDER = 'sort_order';
    public const string IS_VISIBLE = 'is_visible';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';
    public const string DELETED_AT = 'deleted_at';

    protected $table = self::DATABASE_TABLE;

    protected $fillable = [
        self::TITLE,
        self::CLIENT_NAME,
        self::CLIENT_ROLE,
        self::CLIENT_AVATAR_INITIALS,
        self::INDUSTRY,
        self::METRICS,
        self::DESCRIPTION,
        self::TESTIMONIAL,
        self::SORT_ORDER,
        self::IS_VISIBLE,
    ];

    protected $casts = [
        self::INDUSTRY => CaseIndustryEnum::class,
        self::METRICS => 'array',
        self::IS_VISIBLE => 'boolean',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

    protected $attributes = [
        self::SORT_ORDER => 0,
        self::IS_VISIBLE => true,
    ];

    public function scopeVisible($query)
    {
        return $query->where(self::IS_VISIBLE, true);
    }

    public function scopeByIndustry($query, CaseIndustryEnum $industry)
    {
        return $query->where(self::INDUSTRY, $industry->value);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy(self::SORT_ORDER, 'asc');
    }
    public function newEloquentBuilder($query): CaseQueryBuilder
    {
        return new CaseQueryBuilder($query);
    }
}
