<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'automation',      'name' => 'Автоматизация',    'color' => '#33DAFF', 'bg_color' => 'rgba(0,207,255,0.08)'],
            ['slug' => 'ai_for_business', 'name' => 'ИИ для бизнеса',   'color' => '#A78BFA', 'bg_color' => 'rgba(167,139,250,0.1)'],
            ['slug' => 'hr_automation',   'name' => 'HR-автоматизация', 'color' => '#34D399', 'bg_color' => 'rgba(52,211,153,0.1)'],
            ['slug' => 'robots',          'name' => 'Роботы',           'color' => '#F59E0B', 'bg_color' => 'rgba(245,158,11,0.1)'],
            ['slug' => 'technology',      'name' => 'Технологии',       'color' => '#FF9A3C', 'bg_color' => 'rgba(255,120,0,0.08)'],
            ['slug' => 'case',            'name' => 'Кейс',             'color' => '#10B981', 'bg_color' => 'rgba(16,185,129,0.1)'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $cat['slug']],
                array_merge($cat, [
                    'id'         => Str::uuid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
