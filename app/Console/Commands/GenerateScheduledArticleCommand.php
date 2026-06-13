<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\GenerateArticleJob;
use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class GenerateScheduledArticleCommand extends Command
{
    protected $signature   = 'articles:generate-scheduled';
    protected $description = 'Generate an article based on saved schedule settings';

    private const DEFAULT_PROMPT = <<<PROMPT
Ты — профессиональный автор статей для блога о робототехнике и AI-автоматизации бизнеса.
Напиши статью строго в формате JSON (без markdown-блоков, без пояснений — только JSON).

Структура JSON:
{
  "title": "Заголовок статьи",
  "description": "Краткое описание для карточки (1-2 предложения, до 200 символов)",
  "content": "Полный HTML-контент статьи с тегами <p>, <h2>, <h3>, <ul>, <li>, <strong>",
  "reading_time": 7
}

Требования:
- Статья на русском языке, минимум 600 слов
- Только валидный JSON, без лишнего текста
PROMPT;

    public function handle(): int
    {
        $settings = DB::table('settings')
            ->whereIn('key', [
                'article_generation_prompt',
                'article_generation_category_slug',
                'article_generation_enabled',
                'article_schedule_mode',
                'article_schedule_cron',
                'article_schedule_preset',
                'article_schedule_once_at',
                'article_schedule_once_fired',
            ])
            ->pluck('value', 'key');

        // Проверка включена ли автогенерация
        if (($settings['article_generation_enabled'] ?? '1') !== '1') {
            $this->info('Scheduled generation is disabled. Skipping.');
            return self::SUCCESS;
        }

        $mode = $settings['article_schedule_mode'] ?? 'preset';
        $now = now();

        $shouldRun = false;

        if ($mode === 'once') {
            // Режим "Один раз"
            if (($settings['article_schedule_once_fired'] ?? '0') === '1') {
                $this->info('One-time generation already fired. Skipping.');
                return self::SUCCESS;
            }

            $onceAt = $settings['article_schedule_once_at'] ?? null;
            if (!$onceAt) {
                $this->error('Mode is "once" but once_at is not set.');
                return self::FAILURE;
            }

            $target = Carbon::parse($onceAt);
            $nowFlat = $now->copy()->startOfMinute();

            if ($nowFlat->equalTo($target->startOfMinute())) {
                $shouldRun = true;
                $this->markOnceFired();
                $this->info('One-time schedule matched, running...');
            } else {
                if ($now->isAfter($target)) {
                    $this->warn("One-time schedule missed (was {$target}). Marking as fired.");
                    $this->markOnceFired();
                }
                $this->info('Not time for one-time schedule. Target: ' . $target);
                return self::SUCCESS;
            }

        } elseif ($mode === 'preset') {
            $preset = $settings['article_schedule_preset'] ?? 'every_monday';
            $presets = [
                'every_monday'  => '0 9 * * 1',
                'every_day'     => '0 9 * * *',
                'twice_a_week'  => '0 9 * * 1,4',
                'every_weekday' => '0 9 * * 1-5',
                'twice_a_month' => '0 9 1,15 * *',
                'every_month'   => '0 9 1 * *',
            ];
            $cron = $presets[$preset] ?? '0 9 * * 1';

            if ($this->checkCronTime($cron, $now)) {
                $shouldRun = true;
                $this->info("Preset '{$preset}' matched, running...");
            } else {
                $this->info("Not time for preset '{$preset}'. Cron: {$cron}");
                return self::SUCCESS;
            }

        } elseif ($mode === 'custom') {
            $cron = $settings['article_schedule_cron'] ?? '0 9 * * 1';

            if ($this->checkCronTime($cron, $now)) {
                $shouldRun = true;
                $this->info("Custom cron '{$cron}' matched, running...");
            } else {
                $this->info("Not time for custom cron: {$cron}");
                return self::SUCCESS;
            }
        } else {
            $this->error("Unknown mode: {$mode}");
            return self::FAILURE;
        }

        // ========== ГЕНЕРАЦИЯ ==========
        if (!$shouldRun) {
            return self::SUCCESS;
        }

        $prompt = $settings['article_generation_prompt'] ?? null;
        if (!$prompt) {
            $this->warn('No prompt in DB — using built-in default prompt.');
            $prompt = self::DEFAULT_PROMPT;
        }

        $categorySlug = $settings['article_generation_category_slug'] ?? 'technology';

        $category = DB::table('categories')->where('slug', $categorySlug)->first();
        if (!$category) {
            $this->warn("Category with slug '{$categorySlug}' not found, creating it.");
            $categoryId = DB::table('categories')->insertGetId([
                'slug' => $categorySlug,
                'name' => $categorySlug,
                'color' => '#00CFFF',
                'bg_color' => 'rgba(0,207,255,0.08)',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $category = (object) ['id' => $categoryId, 'slug' => $categorySlug];
        }

        GenerateArticleJob::dispatch($prompt, $categorySlug);

        $this->info("✅ Dispatched. Category slug: \"{$categorySlug}\"");
        Log::info('GenerateScheduledArticleCommand: dispatched', [
            'category_slug' => $categorySlug,
            'mode' => $mode,
            'has_prompt' => !empty($prompt)
        ]);

        return self::SUCCESS;
    }

    /**
     * Проверяет, совпадает ли текущее время с cron-выражением
     */
    private function checkCronTime(string $cron, Carbon $now): bool
    {
        try {
            $cronExpression = CronExpression::factory($cron);
            $nextRun = $cronExpression->getNextRunDate();
            $lastRun = $cronExpression->getPreviousRunDate();

            $nowFlat = $now->copy()->startOfMinute();
            $lastRunFlat = Carbon::parse($lastRun)->startOfMinute();

            return $nowFlat->equalTo($lastRunFlat);
        } catch (\Exception $e) {
            Log::error('Cron parse error', ['cron' => $cron, 'error' => $e->getMessage()]);
            return false;
        }
    }

    private function markOnceFired(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'article_schedule_once_fired'],
            ['value' => '1', 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
