<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@business-robotics.ru'],
            [
                'name' => 'Administrator',
                'email' => 'admin@business-robotics.ru',
                'password' => Hash::make('12345678'),
                'is_admin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@business-robotics.ru'],
            [
                'name' => 'Test Admin',
                'email' => 'test@business-robotics.ru',
                'password' => Hash::make('12345678'),
                'is_admin' => true,
            ]
        );

        $this->command->info('✅ Admin users created:');
        $this->command->info('   admin@business-robotics.ru / 12345678');
        $this->command->info('   test@business-robotics.ru / 12345678');
    }
}
