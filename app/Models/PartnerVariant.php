<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Partner\PartnerVariantTypeEnum;
use App\Enums\Partner\PartnerStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class PartnerVariant extends Model
{
    use HasFactory, HasUuids;

    public const string DATABASE_TABLE = 'partner_variants';

    public const string ID = 'id';
    public const string TYPE = 'type';
    public const string TITLE = 'title';
    public const string DESCRIPTION = 'description';
    public const string PERCENTAGE = 'percentage';
    public const string MIN_AMOUNT = 'min_amount';
    public const string AMOUNT_LABEL = 'amount_label';
    public const string BADGE_COLOR = 'badge_color';
    public const string BADGE_BG = 'badge_bg';
    public const string TAGS = 'tags';
    public const string SORT_ORDER = 'sort_order';
    public const string IS_ACTIVE = 'is_active';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';

    protected $table = self::DATABASE_TABLE;

    public $timestamps = true;

    protected $fillable = [
        self::TYPE,
        self::TITLE,
        self::DESCRIPTION,
        self::PERCENTAGE,
        self::MIN_AMOUNT,
        self::AMOUNT_LABEL,
        self::BADGE_COLOR,
        self::BADGE_BG,
        self::TAGS,
        self::SORT_ORDER,
        self::IS_ACTIVE,
    ];

    protected $casts = [
        self::TYPE => PartnerVariantTypeEnum::class,
        self::PERCENTAGE => 'integer',
        self::MIN_AMOUNT => 'integer',
        self::TAGS => 'array',
        self::IS_ACTIVE => 'boolean',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];

    protected $attributes = [
        self::SORT_ORDER => 0,
        self::IS_ACTIVE => true,
    ];

    // Scope для активных записей
    public function scopeActive($query)
    {
        return $query->where(self::IS_ACTIVE, true);
    }

    // Scope для сортировки по полю sort_order
    public function scopeOrdered($query)
    {
        return $query->orderBy(self::SORT_ORDER, 'asc');
    }

    public function scopeOrderBySortOrder($query)
    {
        return $query->orderBy(self::SORT_ORDER, 'asc');
    }

    public function scopeByType($query, PartnerVariantTypeEnum $type)
    {
        return $query->where(self::TYPE, $type->value);
    }
}
