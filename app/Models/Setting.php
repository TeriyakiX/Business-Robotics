<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['group', 'key', 'value', 'type'];

    public static function getAllGrouped(): array
    {
        $result = [];
        $settings = self::all();

        foreach ($settings as $setting) {
            $result[$setting->group][$setting->key] = $setting->value;
        }

        return $result;
    }
}
