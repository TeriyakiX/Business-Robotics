<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agent>
 */
final class AgentFactory extends Factory
{
    protected $model = Agent::class;

    public function definition(): array
    {
        return [
            Agent::NAME => $this->faker->unique()->company(),
            Agent::TAG => $this->faker->word(),
            Agent::DESCRIPTION => $this->faker->paragraph(),
            Agent::FEATURES => $this->faker->randomElements([
                'До 10 000 звонков в сутки',
                'Интеграция с любой CRM',
                'Распознавание возражений и автоответы',
                'Голосовые и SMS-уведомления',
                'Автоматический перенос записей',
                '−60% неявок клиентов',
                'Голос и чат одновременно',
                'Обучается на ваших данных',
                'Эскалация сложных кейсов',
                'Адаптация за 5 дней',
                '−70% нагрузки на HR',
                'Автотестирование и аттестации',
            ], $this->faker->numberBetween(3, 6)),
            Agent::ICON_NAME => $this->faker->randomElement(['phone', 'bell', 'message', 'box']),
            Agent::SORT_ORDER => $this->faker->numberBetween(0, 100),
            Agent::IS_ACTIVE => true,
            Agent::CREATED_AT => now(),
            Agent::UPDATED_AT => now(),
        ];
    }
}
