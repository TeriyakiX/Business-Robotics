<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AgentSeeder::class,
            CaseSeeder::class,
            ArticleSeeder::class,
            ProcessStepSeeder::class,
            MarqueeItemSeeder::class,
            PartnerSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
