<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProcessStepStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class ProcessStep extends Model
{
    use HasFactory, HasUuids;

    public const string DATABASE_TABLE = 'process_steps';

    public const string ID = 'id';
    public const string NUMBER = 'number';
    public const string TITLE = 'title';
    public const string DESCRIPTION = 'description';
    public const string DAY_RANGE = 'day_range';
    public const string SORT_ORDER = 'sort_order';
    public const string IS_ACTIVE = 'is_active';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';

    protected $table = self::DATABASE_TABLE;

    public $timestamps = true;

    protected $fillable = [
        self::NUMBER,
        self::TITLE,
        self::DESCRIPTION,
        self::DAY_RANGE,
        self::SORT_ORDER,
        self::IS_ACTIVE,
    ];

    protected $casts = [
        self::NUMBER => 'integer',
        self::IS_ACTIVE => 'boolean',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
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
        return $query->orderBy(self::SORT_ORDER, 'asc')
            ->orderBy(self::NUMBER, 'asc');
    }
}
