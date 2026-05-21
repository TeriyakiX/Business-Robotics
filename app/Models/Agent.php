<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Agent\AgentStatusEnum;
use App\QueryBuilders\AgentQueryBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Agent extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const string DATABASE_TABLE = 'agents';

    public const string ID = 'id';
    public const string NAME = 'name';
    public const string TAG = 'tag';
    public const string DESCRIPTION = 'description';
    public const string FEATURES = 'features';
    public const string ICON_NAME = 'icon_name';
    public const string SORT_ORDER = 'sort_order';
    public const string IS_ACTIVE = 'is_active';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';
    public const string DELETED_AT = 'deleted_at';

    protected $table = self::DATABASE_TABLE;

    protected $fillable = [
        self::NAME,
        self::TAG,
        self::DESCRIPTION,
        self::FEATURES,
        self::ICON_NAME,
        self::SORT_ORDER,
        self::IS_ACTIVE,
    ];

    protected $casts = [
        self::FEATURES => 'array',
        self::IS_ACTIVE => 'boolean',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

    protected $attributes = [
        self::SORT_ORDER => 0,
        self::IS_ACTIVE => true,
    ];

    public function scopeActive($query)
    {
        return $query->where(self::IS_ACTIVE, true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy(self::SORT_ORDER, 'asc');
    }

    public function newEloquentBuilder($query): AgentQueryBuilder
    {
        return new AgentQueryBuilder($query);
    }
}
