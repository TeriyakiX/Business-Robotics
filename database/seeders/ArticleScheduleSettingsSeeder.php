<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleScheduleSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now  = now();

        $category = DB::table('categories')->where('slug', 'technology')->first();
        if (!$category) {
            $categoryId = DB::table('categories')->insertGetId([
                'slug' => 'technology',
                'name' => 'Технологии',
                'description' => 'Статьи о технологиях и инновациях',
                'color' => '#00CFFF',
                'bg_color' => 'rgba(0,207,255,0.08)',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $categorySlug = 'technology';
        } else {
            $categorySlug = $category->slug;
        }

        $rows = [
            [
                'group' => 'articles',
                'key'   => 'article_generation_enabled',
                'value' => '1',
                'type'  => 'boolean',
            ],
            [
                'group' => 'articles',
                'key'   => 'article_generation_prompt',
                'value' => null,
                'type'  => 'text',
            ],
            [
                'group' => 'articles',
                'key'   => 'article_generation_category_slug',
                'value' => $categorySlug,
                'type'  => 'text',
            ],
            [
                'group' => 'articles',
                'key'   => 'article_schedule_enabled',
                'value' => '1',
                'type'  => 'boolean',
            ],
            [
                'group' => 'articles',
                'key'   => 'article_schedule_mode',
                'value' => 'preset',
                'type'  => 'text',
            ],
            [
                'group' => 'articles',
                'key'   => 'article_schedule_preset',
                'value' => 'every_monday',
                'type'  => 'text',
            ],
            [
                'group' => 'articles',
                'key'   => 'article_schedule_cron',
                'value' => '0 9 * * 1',
                'type'  => 'text',
            ],
            [
                'group' => 'articles',
                'key'   => 'article_schedule_once_at',
                'value' => null,
                'type'  => 'datetime',
            ],
            [
                'group' => 'articles',
                'key'   => 'article_schedule_once_fired',
                'value' => '0',
                'type'  => 'boolean',
            ],
        ];

        foreach ($rows as $row) {
            DB::table('settings')->updateOrInsert(
                ['key' => $row['key']],
                array_merge($row, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        $this->command->info('Article schedule settings seeded successfully.');
        $this->command->info("Category slug: {$categorySlug}");
    }
}
