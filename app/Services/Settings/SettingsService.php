<?php

declare(strict_types=1);

namespace App\Services\Settings;

use App\DTOs\Settings\SettingsUpdateDto;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final readonly class SettingsService
{
    public function getAll(): array
    {
        return Setting::getAllGrouped();
    }

    public function update(SettingsUpdateDto $dto): void
    {
        foreach ($dto->toArray() as $key => $value) {
            if ($value !== null && $value !== '') {
                $group = match (true) {
                    str_starts_with($key, 'hero_') => 'hero',
                    str_starts_with($key, 'cta_') => 'cta',
                    str_starts_with($key, 'contact_form_') => 'contact_form',
                    str_starts_with($key, 'contact_') => 'contacts',
                    str_starts_with($key, 'footer_') => 'footer',
                    default => 'general',
                };

                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    [
                        'group' => $group,
                        'value' => $this->normalizeValue($value),
                        'type' => $this->detectType($value),
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );
            }
        }
    }

    public function updateHeroWithFiles(array $textData, ?UploadedFile $background = null, ?UploadedFile $media = null): void
    {
        foreach ($textData as $key => $value) {
            if ($value !== null && $value !== '') {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    [
                        'group' => 'hero',
                        'value' => $this->normalizeValue($value),
                        'type' => $this->detectType($value),
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );
            }
        }

        if ($background) {
            $old = DB::table('settings')->where('key', 'hero_background')->first();
            if ($old && $old->value && Storage::disk('public')->exists($old->value)) {
                Storage::disk('public')->delete($old->value);
            }
            $path = $background->store('settings/hero', 'public');
            DB::table('settings')->updateOrInsert(
                ['key' => 'hero_background'],
                ['group' => 'hero', 'value' => $path, 'type' => 'image', 'updated_at' => now(), 'created_at' => now()]
            );
        }

        if ($media) {
            $old = DB::table('settings')->where('key', 'hero_media')->first();
            if ($old && $old->value && Storage::disk('public')->exists($old->value)) {
                Storage::disk('public')->delete($old->value);
            }
            $path = $media->store('settings/hero', 'public');
            $type = str_starts_with($media->getMimeType(), 'video/') ? 'video' : 'image';
            DB::table('settings')->updateOrInsert(
                ['key' => 'hero_media'],
                ['group' => 'hero', 'value' => $path, 'type' => $type, 'updated_at' => now(), 'created_at' => now()]
            );
            DB::table('settings')->updateOrInsert(
                ['key' => 'hero_media_type'],
                ['group' => 'hero', 'value' => $type, 'type' => 'text', 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function updateSocials(array $socials): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'socials'],
            [
                'group' => 'contacts',
                'value' => json_encode($socials, JSON_UNESCAPED_UNICODE),
                'type' => 'json',
                'updated_at' => now(),
                'created_at' => now()
            ]
        );
    }

    public function updateSettings(array $data): void
    {
        foreach ($data as $key => $value) {
            // Пропускаем null и пустые строки, но сохраняем массивы
            if ($value === null || $value === '') {
                continue;
            }

            // Если это массив - обрабатываем специально
            if (is_array($value)) {
                $this->updateArraySetting($key, $value);
                continue;
            }

            $group = match (true) {
                str_starts_with($key, 'hero_') => 'hero',
                str_starts_with($key, 'agents_') => 'agents',
                str_starts_with($key, 'cases_') => 'cases',
                str_starts_with($key, 'process_') => 'process',
                str_starts_with($key, 'blog_') => 'blog',
                str_starts_with($key, 'partner_') => 'partners',
                str_starts_with($key, 'partners_') => 'partners',
                str_starts_with($key, 'cta_') => 'cta',
                str_starts_with($key, 'contact_form_') => 'contact_form',
                str_starts_with($key, 'contact_') => 'contacts',
                str_starts_with($key, 'footer_') => 'footer',
                str_starts_with($key, 'marquee_') => 'marquee',
                default => 'general',
            };

            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'group' => $group,
                    'value' => $this->normalizeValue($value),
                    'type' => $this->detectType($value),
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
        }
    }

    /**
     * Обработка массивов для сохранения как JSON
     */
    private function updateArraySetting(string $key, array $value): void
    {
        $group = match (true) {
            str_starts_with($key, 'hero_') => 'hero',
            str_starts_with($key, 'agents_') => 'agents',
            str_starts_with($key, 'cases_') => 'cases',
            str_starts_with($key, 'process_') => 'process',
            str_starts_with($key, 'blog_') => 'blog',
            str_starts_with($key, 'partner_') => 'partners',
            str_starts_with($key, 'partners_') => 'partners',
            str_starts_with($key, 'cta_') => 'cta',
            str_starts_with($key, 'contact_form_') => 'contact_form',
            str_starts_with($key, 'contact_') => 'contacts',
            str_starts_with($key, 'footer_') => 'footer',
            str_starts_with($key, 'marquee_') => 'marquee',
            default => 'general',
        };

        // Если массив пустой - сохраняем как пустой JSON массив
        $jsonValue = empty($value) ? '[]' : json_encode($value, JSON_UNESCAPED_UNICODE);

        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $jsonValue,
                'type' => 'json',
                'updated_at' => now(),
                'created_at' => now()
            ]
        );
    }

    /**
     * Нормализация значения для сохранения
     */
    private function normalizeValue(mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_null($value)) {
            return '';
        }

        return (string)$value;
    }

    /**
     * Определение типа значения
     */
    private function detectType(mixed $value): string
    {
        if (is_array($value)) {
            return 'json';
        }

        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_numeric($value)) {
            return 'number';
        }

        return 'text';
    }
}
