<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Article;
use App\Models\Category;
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
        private readonly string $categorySlug,
    ) {}

    public function handle(): void
    {
        $apiKey = config('services.anthropic.api_key');

        if (!$apiKey) {
            Log::error('GenerateArticleJob: ANTHROPIC_API_KEY not set in .env');
            return;
        }

        $category = Category::where('slug', $this->categorySlug)->first();

        if (!$category) {
            Log::error('GenerateArticleJob: category not found', ['slug' => $this->categorySlug]);
            $category = Category::create([
                'slug' => $this->categorySlug,
                'name' => $this->categorySlug,
                'color' => '#00CFFF',
                'bg_color' => 'rgba(0,207,255,0.08)',
            ]);
        }

        Log::info('GenerateArticleJob: starting generation', [
            'prompt' => $this->prompt,
            'category_slug' => $this->categorySlug,
            'category_id' => $category->id,
        ]);
        
        $topic = trim($this->prompt) !== ''
            ? $this->prompt
            : 'выбери случайную отрасль из списка: Ритейл/E-commerce, Недвижимость, Онлайн-образование, Финтех, Логистика, HR и рекрутинг, Медицинские клиники, Юриспруденция, B2B-продажи';

        $systemPrompt = <<<EOT
Ты — ведущий SEO-специалист и контент-маркетолог B2B-сервиса по внедрению ИИ-агентов для бизнеса. Твоя задача — написать экспертную, уникальную и SEO-оптимизированную статью для сайта.

### 1. ТЕМА И НИША
ТЕМА/НИША: {$topic}

### 2. ЦЕЛЕВАЯ АУДИТОРИЯ
Владельцы бизнеса, CEO, операционные директора и руководители отделов. Они ценят конкретику, окупаемость (ROI), снижение издержек и автоматизацию рутины, но не любят сухой технический жаргон.

### 3. СТРУКТУРА СТАТЬИ
Сгенерируй материал строго по следующей структуре:

1. SEO-Метаданные:
- Meta Title (до 60 символов, с главным ключевым словом)
- Meta Description (до 160 символов, с призывом к действию)
- H1 Заголовок (цепляющий, для читателя)
- ЧПУ / URL Slug (на латинице)

2. Введение:
- Главная боль/проблема отрасли (в цифрах или реальных сценариях).
- Как ИИ-агенты кардинально меняют подходы к решению этой проблемы.

3. Основная часть (2–3 раздела с H2):
- Конкретные кейсы применения ИИ-агентов в этой нише (например: обработка лидов 24/7, квалификация заявок, автоматизация документации).
- Сравнение: "Как было раньше (вручную)" vs "Как стало с ИИ-агентом".
- Пошаговый сценарий или архитектура внедрения (простым языком).

4. Экономическая выгода и ROI:
- За счет чего экономится бюджет или увеличивается конверсия (пример расчета выгоды).

5. Заключение и CTA:
- Краткий вывод.
- Органичный призыв к действию (затестить ИИ-агента, получить демо или внедрить решения для своего бизнеса).

### 4. ТРЕБОВАНИЯ К SEO И СТИЛЮ
- Тон: Экспертный, убедительный, деловой, но доступный (Helpful Content).
- Запрет на штампы: Не используй клише: «В современном мире», «ИИ развивается стремительными темпами», «Ни для кого не секрет», «Революционный инструмент», «Парадигма».
- Структура: Короткие абзацы (3–4 строки), маркированные и нумерованные списки, выделение ключевых мыслей жирным шрифтом.
- Объем: 1200–1800 слов.

### 5. ФОРМАТ ОТВЕТА (ОБЯЗАТЕЛЬНО)
Верни результат строго в формате JSON, без markdown-блоков, без пояснений — только валидный JSON:
{
  "title": "H1 заголовок статьи (цепляющий, для читателя)",
  "description": "Meta Description до 160 символов, с призывом к действию",
  "content": "Полный HTML-контент статьи с тегами <p>, <h2>, <h3>, <ul>, <li>, <strong>. Включи в начало статьи ЧПУ/slug и Meta Title как HTML-комментарий <!-- meta_title: ...; slug: ... --> перед первым тегом.",
  "reading_time": 7
}

Только валидный JSON, никакого текста до или после него.
EOT;

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(100)->post('http://64.188.58.83/v1/messages', [
            'model' => 'claude-haiku-4-5-20251001',
            'max_tokens' => 8000,
            'system' => $systemPrompt,
            'messages' => [
                ['role' => 'user', 'content' => "Напиши статью на тему: {$topic}"],
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

        $text = preg_replace('/^```json\s*/m', '', $text);
        $text = preg_replace('/^```\s*/m', '', $text);
        $text = trim($text);

        $articleData = json_decode($text, true);

        if (!$articleData || !isset($articleData['title'], $articleData['content'])) {
            Log::error('GenerateArticleJob: failed to parse JSON', ['raw' => $text]);
            $this->fail(new \Exception('Failed to parse article JSON from Claude'));
            return;
        }

        $baseSlug = Str::slug($articleData['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Article::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Article::create([
            'slug'         => $slug,
            'title'        => $articleData['title'],
            'description'  => $articleData['description'] ?? '',
            'content'      => $articleData['content'],
            'category_id'  => $category->id,
            'reading_time' => $articleData['reading_time'] ?? 5,
            'is_published' => true,
            'published_at' => now(),
            'views_count'  => 0,
        ]);

        Log::info('GenerateArticleJob: article created', [
            'slug' => $slug,
            'title' => $articleData['title'],
            'category_id' => $category->id,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateArticleJob failed', [
            'prompt' => $this->prompt,
            'category' => $this->categorySlug,
            'error' => $exception->getMessage(),
        ]);
    }
}
