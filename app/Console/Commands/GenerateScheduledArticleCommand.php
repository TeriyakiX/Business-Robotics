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
            $targetFlat = $target->copy()->startOfMinute();

            if ($nowFlat->equalTo($targetFlat)) {
                $shouldRun = true;
                $this->markOnceFired();
            } else {
                if ($now->isAfter($target)) {
                    $this->markOnceFired();
                }
                return self::SUCCESS;
            }

        } elseif ($mode === 'preset' || $mode === 'custom') {
            // ВАЖНО: и preset, и custom теперь читают ОДНО И ТО ЖЕ поле
            // article_schedule_cron. Именно его пишет ArticleScheduleController
            // при сохранении расписания — как для готовых пресетов, так и для
            // произвольного cron-выражения. Раньше preset-режим брал cron из
            // отдельного захардкоженного массива внутри этого файла, из-за чего
            // правки cron через контроллер/UI для пресетов игнорировались.
            $cron = $settings['article_schedule_cron'] ?? '0 9 * * 1';

            try {
                $shouldRun = CronExpression::factory($cron)->isDue($now);
            } catch (\Exception $e) {
                $this->error("Invalid cron expression '{$cron}': " . $e->getMessage());
                Log::error('GenerateScheduledArticleCommand: invalid cron expression', [
                    'cron' => $cron,
                    'mode' => $mode,
                    'error' => $e->getMessage(),
                ]);
                return self::FAILURE;
            }

            if (!$shouldRun) {
                return self::SUCCESS;
            }
        } else {
            $this->error("Unknown mode: {$mode}");
            return self::FAILURE;
        }

        if (!$shouldRun) {
            return self::SUCCESS;
        }

        $prompt = $settings['article_generation_prompt'] ?? null;
        if (!$prompt) {
            $prompt = self::DEFAULT_PROMPT;
        }

        $categorySlug = $settings['article_generation_category_slug'] ?? 'technology';

        GenerateArticleJob::dispatch($prompt, $categorySlug);

        $this->info("Dispatched. Category slug: {$categorySlug}");
        Log::info('GenerateScheduledArticleCommand: dispatched', [
            'category_slug' => $categorySlug,
            'mode' => $mode,
        ]);

        return self::SUCCESS;
    }

    private function markOnceFired(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'article_schedule_once_fired'],
            ['value' => '1', 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
