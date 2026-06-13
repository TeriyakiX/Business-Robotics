<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\GenerateArticleJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class GenerateScheduledArticleCommand extends Command
{
    protected $signature   = 'articles:generate-scheduled';
    protected $description = 'Generate an article based on saved schedule settings';

    private const DEFAULT_PROMPT = <<<PROMPT
Ты — профессиональный автор статей для блога о робототехнике и AI-автоматизации бизнеса.
Ты публикуешься раз в неделю (иногда по отдельному запросу).

Выбери одну актуальную тему из следующих направлений и напиши статью:
- Применение роботов в логистике, производстве или ритейле
- AI-автоматизация бизнес-процессов (HR, финансы, маркетинг, поддержка клиентов)
- Кейсы внедрения робототехники в российских или мировых компаниях
- Тренды и прогнозы в области AI и роботов на ближайшие годы
- Сравнение решений: какой инструмент автоматизации выбрать и почему

Напиши статью строго в формате JSON (без markdown-блоков, без пояснений — только JSON).

Структура JSON:
{
  "title": "Заголовок статьи",
  "description": "Краткое описание для карточки (1-2 предложения, до 200 символов)",
  "content": "Полный HTML-контент статьи с тегами <p>, <h2>, <h3>, <ul>, <li>, <strong>",
  "reading_time": 7
}

Требования:
- Статья на русском языке
- Заголовок — конкретный и SEO-friendly
- Контент — минимум 600 слов, структурированный с подзаголовками
- reading_time — реалистичное время чтения в минутах
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
                'article_schedule_once_at',
                'article_schedule_once_fired',
            ])
            ->pluck('value', 'key');

        if (($settings['article_generation_enabled'] ?? '1') !== '1') {
            $this->info('Scheduled generation is disabled. Skipping.');
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

        $mode = $settings['article_schedule_mode'] ?? 'preset';

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

            $target  = Carbon::parse($onceAt);
            $nowFlat = now()->startOfMinute();

            if (!$nowFlat->equalTo($target->startOfMinute())) {
                if (now()->isAfter($target)) {
                    $this->warn("One-time schedule missed (was {$target}). Marking as fired.");
                    $this->markOnceFired();
                }
                return self::SUCCESS;
            }

            $this->markOnceFired();
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

    private function markOnceFired(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'article_schedule_once_fired'],
            ['value' => '1', 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
