<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Contact\ContactStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class ContactRequest extends Model
{
    use HasFactory, HasUuids;

    public const string DATABASE_TABLE = 'contact_requests';

    public const string ID = 'id';
    public const string NAME = 'name';
    public const string PHONE = 'phone';
    public const string COMPANY = 'company';
    public const string STATUS = 'status';
    public const string PROCESSED_AT = 'processed_at';
    public const string NOTES = 'notes';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';

    protected $table = self::DATABASE_TABLE;

    public $timestamps = true;

    protected $fillable = [
        self::NAME,
        self::PHONE,
        self::COMPANY,
        self::STATUS,
        self::PROCESSED_AT,
        self::NOTES,
    ];

    protected $casts = [
        self::STATUS => ContactStatusEnum::class,
        self::PROCESSED_AT => 'datetime',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];

    protected $attributes = [
        self::STATUS => ContactStatusEnum::NEW->value,
    ];

    public function scopeNew($query)
    {
        return $query->where(self::STATUS, ContactStatusEnum::NEW->value);
    }

    public function scopeProcessed($query)
    {
        return $query->where(self::STATUS, ContactStatusEnum::PROCESSED->value);
    }

    public function markAsProcessed(?string $notes = null): void
    {
        $this->update([
            self::STATUS => ContactStatusEnum::PROCESSED->value,
            self::PROCESSED_AT => now(),
            self::NOTES => $notes,
        ]);
    }

    public function markAsContacted(): void
    {
        $this->update([
            self::STATUS => ContactStatusEnum::CONTACTED->value,
        ]);
    }

    public function markAsRejected(?string $notes = null): void
    {
        $this->update([
            self::STATUS => ContactStatusEnum::REJECTED->value,
            self::NOTES => $notes,
        ]);
    }
}
