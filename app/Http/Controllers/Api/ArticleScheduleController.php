<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

final class ArticleScheduleController extends Controller
{
    /**
     * Режимы расписания:
     *  - "once"    — один раз в конкретную дату и время
     *  - "preset"  — один из готовых пресетов (повторяющееся)
     *  - "custom"  — произвольное cron-выражение (повторяющееся)
     *
     */
    private const PRESETS = [
        'every_monday'  => ['cron' => '0 9 * * 1',    'label' => 'Каждый понедельник в 9:00'],
        'every_day'     => ['cron' => '0 8 * * *',    'label' => 'Каждый день в 8:00'],
        'twice_a_week'  => ['cron' => '0 9 * * 1,4',  'label' => 'Пн и Чт в 9:00'],
        'every_weekday' => ['cron' => '0 9 * * 1-5',  'label' => 'По будням в 9:00'],
        'twice_a_month' => ['cron' => '0 9 1,15 * *', 'label' => '1-го и 15-го в 9:00'],
        'every_month'   => ['cron' => '0 9 1 * *',    'label' => 'Раз в месяц (1-го) в 9:00'],
    ];

    private const WEEKDAYS_RU = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'];

    /**
     * GET /admin/articles/schedule
     */
    public function show(): JsonResponse
    {
        $settings = DB::table('settings')
            ->whereIn('key', [
                'article_schedule_enabled',
                'article_schedule_mode',
                'article_schedule_preset',
                'article_schedule_cron',
                'article_schedule_once_at',
                'article_schedule_once_fired',
                'article_generation_prompt',
                'article_generation_category_slug',
            ])
            ->pluck('value', 'key');

        $mode   = $settings['article_schedule_mode'] ?? 'preset';
        $cron   = $settings['article_schedule_cron'] ?? '0 9 * * 1';
        $onceAt = $settings['article_schedule_once_at'] ?? null;

        return response()->json([
            'success'     => true,
            'enabled'     => ($settings['article_schedule_enabled'] ?? '1') === '1',
            'mode'        => $mode,
            'preset'      => $settings['article_schedule_preset'] ?? 'every_monday',
            'cron'        => $cron,
            'once_at'     => $onceAt,
            'once_fired'  => ($settings['article_schedule_once_fired'] ?? '0') === '1',
            'prompt'      => $settings['article_generation_prompt'] ?? '',
            'category_slug' => $settings['article_generation_category_slug'] ?? 'technology',
            'presets'     => self::PRESETS,
            'label'       => $mode === 'once'
                ? $this->buildOnceLabel($onceAt)
                : $this->buildCronLabel($cron),
        ]);
    }

    /**
     * PUT /admin/articles/schedule
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
            'mode'    => ['required', Rule::in(['once', 'preset', 'custom'])],

            // once
            'once_at' => [
                'nullable',
                'date',
                Rule::requiredIf(fn() => $request->input('mode') === 'once'),
            ],

            // preset
            'preset'  => [
                'nullable',
                Rule::in(array_keys(self::PRESETS)),
                Rule::requiredIf(fn() => $request->input('mode') === 'preset'),
            ],

            // custom
            'cron'    => [
                'nullable',
                'string',
                'regex:/^(\S+\s){4}\S+$/',
                Rule::requiredIf(fn() => $request->input('mode') === 'custom'),
            ],
        ]);

        $mode = $validated['mode'];
        $now  = now();

        $cron = match ($mode) {
            'once'   => $this->buildOnceCron($validated['once_at']),
            'preset' => self::PRESETS[$validated['preset']]['cron'],
            'custom' => $validated['cron'],
        };

        $label = $mode === 'once'
            ? $this->buildOnceLabel($validated['once_at'])
            : $this->buildCronLabel($cron);

        $rows = [
            'article_schedule_enabled'    => $validated['enabled'] ? '1' : '0',
            'article_schedule_mode'       => $mode,
            'article_schedule_cron'       => $cron,
            'article_generation_enabled'  => $validated['enabled'] ? '1' : '0',
        ];

        if ($mode === 'once') {
            $rows['article_schedule_once_at']     = $validated['once_at'];
            $rows['article_schedule_once_fired']  = '0';
        } elseif ($mode === 'preset') {
            $rows['article_schedule_preset'] = $validated['preset'];
        }

        foreach ($rows as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Расписание сохранено: {$label}",
            'mode'    => $mode,
            'cron'    => $cron,
            'label'   => $label,
        ]);
    }

    /**
     * POST /admin/articles/generation-settings
     */
    public function updateGenerationSettings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt'       => 'nullable|string',
            'category_id'  => 'nullable|integer|exists:categories,id',
            'category_slug' => 'nullable|string|exists:categories,slug',
        ]);

        $now = now();

        DB::table('settings')->updateOrInsert(
            ['key' => 'article_generation_prompt'],
            ['value' => $validated['prompt'] ?? '', 'updated_at' => $now, 'created_at' => $now]
        );

        $categorySlug = null;

        if (!empty($validated['category_slug'])) {
            $categorySlug = $validated['category_slug'];
        } elseif (!empty($validated['category_id']) && is_numeric($validated['category_id'])) {
            $category = DB::table('categories')->where('id', (int)$validated['category_id'])->first();
            if ($category) {
                $categorySlug = $category->slug;
            }
        }

        if ($categorySlug) {
            DB::table('settings')->updateOrInsert(
                ['key' => 'article_generation_category_slug'],
                ['value' => $categorySlug, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Настройки генерации сохранены',
            'category_slug' => $categorySlug
        ]);
    }

    /**
     * Строит cron-выражение из ISO даты для разового запуска.
     */
    private function buildOnceCron(string $isoDatetime): string
    {
        $dt = \Carbon\Carbon::parse($isoDatetime);
        return sprintf('%d %d %d %d *', $dt->minute, $dt->hour, $dt->day, $dt->month);
    }

    private function buildOnceLabel(?string $onceAt): string
    {
        if (!$onceAt) return 'Дата не задана';
        $dt = \Carbon\Carbon::parse($onceAt);
        return 'Один раз: ' . $dt->format('d.m.Y в H:i');
    }

    private function buildCronLabel(string $cron): string
    {
        $parts = preg_split('/\s+/', trim($cron));
        if (count($parts) !== 5) {
            return "cron: {$cron}";
        }

        [$min, $hour, $day, $month, $dow] = $parts;

        $hasFixedTime = ctype_digit($min) && ctype_digit($hour);
        if (!$hasFixedTime) {
            return "cron: {$cron}";
        }

        $time = sprintf('%02d:%02d', (int)$hour, (int)$min);

        $dayIsAny   = $day === '*';
        $monthIsAny = $month === '*';
        $dowIsAny   = $dow === '*';

        if ($dayIsAny && $monthIsAny && $dowIsAny) {
            return "Каждый день в {$time}";
        }

        if ($dayIsAny && $monthIsAny && !$dowIsAny) {
            $days = [];
            foreach (explode(',', $dow) as $token) {
                if (str_contains($token, '-')) {
                    [$a, $b] = array_map('intval', explode('-', $token));
                    for ($i = $a; $i <= $b; $i++) {
                        $days[] = $i % 7;
                    }
                } else {
                    $days[] = ((int)$token) % 7;
                }
            }
            $days = array_unique($days);
            sort($days);
            $labels = array_map(fn($d) => self::WEEKDAYS_RU[$d], $days);
            return 'По дням (' . implode(', ', $labels) . ") в {$time}";
        }

        if (!$dayIsAny && $monthIsAny && $dowIsAny) {
            $days = array_map('trim', explode(',', $day));
            return implode(', ', $days) . "-го числа в {$time}";
        }

        return "cron: {$cron}";
    }
}
