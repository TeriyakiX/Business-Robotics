<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\Article\ArticleCategoryEnum;
use App\Models\Article;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class GenerateArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        private readonly string $prompt,
        private readonly string $category = 'technology',
    ) {}

    public function handle(): void
    {
        $apiKey = config('services.anthropic.api_key');

        if (!$apiKey) {
            Log::error('GenerateArticleJob: ANTHROPIC_API_KEY not set in .env');
            return;
        }

        Log::info('GenerateArticleJob: starting generation', ['prompt' => $this->prompt]);

        $systemPrompt = <<<EOT
Ты — профессиональный автор статей для блога о робототехнике и AI-автоматизации бизнеса.
Напиши статью строго в формате JSON (без markdown-блоков, без пояснений — только JSON).

Структура JSON:
{
  "title": "Заголовок статьи",
  "description": "Краткое описание для карточки (1-2 предложения, до 200 символов)",
  "content": "Полный HTML-контент статьи с тегами <p>, <h2>, <h3>, <ul>, <li>, <strong>",
  "reading_time": 7,
  "category": "technology"
}

Допустимые значения category: automation, ai_for_business, hr_automation, robots, technology, case

Требования:
- Статья на русском языке
- Заголовок — конкретный и SEO-friendly
- Контент — минимум 600 слов, структурированный с подзаголовками
- reading_time — реалистичное время чтения в минутах
- Только валидный JSON, без лишнего текста
EOT;

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(100)->post('http://72.56.25.171/v1/messages', [
            'model' => 'claude-haiku-4-5-20251001',
            'max_tokens' => 8000,
            'system' => $systemPrompt,
            'messages' => [
                ['role' => 'user', 'content' => $this->prompt],
            ],
        ]);

        if (!$response->successful()) {
            Log::error('GenerateArticleJob: API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $this->fail(new \Exception('Claude API error: ' . $response->status()));
            return;
        }

        $data = $response->json();
        $text = collect($data['content'] ?? [])
            ->where('type', 'text')
            ->pluck('text')
            ->implode('');

        // Убираем markdown-блоки если Claude всё же добавил
        $text = preg_replace('/^```json\s*/m', '', $text);
        $text = preg_replace('/^```\s*/m', '', $text);
        $text = trim($text);

        $article = json_decode($text, true);

        if (!$article || !isset($article['title'], $article['content'])) {
            Log::error('GenerateArticleJob: failed to parse JSON', ['raw' => $text]);
            $this->fail(new \Exception('Failed to parse article JSON from Claude'));
            return;
        }

        // Определяем категорию
        $categoryValue = $article['category'] ?? $this->category;
        $category = ArticleCategoryEnum::tryFrom($categoryValue) ?? ArticleCategoryEnum::from('technology');

        // Генерируем уникальный slug
        $baseSlug = Str::slug($article['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Article::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Article::create([
            'slug'         => $slug,
            'title'        => $article['title'],
            'description'  => $article['description'] ?? '',
            'content'      => $article['content'],
            'category'     => $category->value,
            'reading_time' => $article['reading_time'] ?? 5,
            'is_published' => true,
            'published_at' => now(),
            'views_count'  => 0,
        ]);

        Log::info('GenerateArticleJob: article created', ['slug' => $slug, 'title' => $article['title']]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateArticleJob failed', [
            'prompt' => $this->prompt,
            'error' => $exception->getMessage(),
        ]);
    }
}
