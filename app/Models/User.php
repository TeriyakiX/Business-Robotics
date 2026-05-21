<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

final class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const string ID = 'id';
    public const string NAME = 'name';
    public const string EMAIL = 'email';
    public const string IS_ADMIN = 'is_admin';
    public const string AVATAR = 'avatar';
    public const string EMAIL_VERIFIED_AT = 'email_verified_at';
    public const string PASSWORD = 'password';
    public const string REMEMBER_TOKEN = 'remember_token';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
        self::IS_ADMIN,
        self::AVATAR,
    ];

    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    protected $casts = [
        self::EMAIL_VERIFIED_AT => 'datetime',
        self::IS_ADMIN => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}
