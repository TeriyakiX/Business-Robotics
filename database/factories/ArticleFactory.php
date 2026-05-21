<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Article\ArticleCategoryEnum;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
final class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(8);

        return [
            Article::SLUG => Str::slug($title),
            Article::TITLE => $title,
            Article::CATEGORY => $this->faker->randomElement(ArticleCategoryEnum::cases()),
            Article::CATEGORY_COLOR => null,
            Article::CATEGORY_BG_COLOR => null,
            Article::DESCRIPTION => $this->faker->paragraph(2),
            Article::CONTENT => $this->generateContent(),
            Article::READING_TIME => $this->faker->numberBetween(5, 15),
            Article::PUBLISHED_AT => $this->faker->dateTimeBetween('-6 months', 'now'),
            Article::IS_PUBLISHED => true,
            Article::VIEWS_COUNT => $this->faker->numberBetween(100, 10000),
            Article::CREATED_AT => now(),
            Article::UPDATED_AT => now(),
        ];
    }

    private function generateContent(): string
    {
        $paragraphs = [];
        for ($i = 0; $i < 6; $i++) {
            $paragraphs[] = '<p>' . $this->faker->paragraph(5) . '</p>';
        }
        return implode('', $paragraphs);
    }
}
