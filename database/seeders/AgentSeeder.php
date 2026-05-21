<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

final class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            [
                Agent::NAME => 'AI-LeadGen',
                Agent::TAG => 'Генерация лидов',
                Agent::DESCRIPTION => 'Автоматический исходящий обзвон базы клиентов, квалификация лидов и передача горячих контактов менеджерам в реальном времени.',
                Agent::FEATURES => [
                    'До 10 000 звонков в сутки',
                    'Интеграция с любой CRM',
                    'Распознавание возражений и автоответы',
                ],
                Agent::ICON_NAME => 'phone',
                Agent::SORT_ORDER => 1,
                Agent::IS_ACTIVE => true,
            ],
            [
                Agent::NAME => 'AI-Manager',
                Agent::TAG => 'Уведомления',
                Agent::DESCRIPTION => 'Автоматические напоминания о записях, подтверждения бронирований и уведомления о статусе заказа. Снижает неявки на 60%.',
                Agent::FEATURES => [
                    'Голосовые и SMS-уведомления',
                    'Автоматический перенос записей',
                    '−60% неявок клиентов',
                ],
                Agent::ICON_NAME => 'bell',
                Agent::SORT_ORDER => 2,
                Agent::IS_ACTIVE => true,
            ],
            [
                Agent::NAME => 'AI-Consultant',
                Agent::TAG => 'Поддержка 24/7',
                Agent::DESCRIPTION => 'Мгновенные ответы на вопросы клиентов по голосу и тексту. Обрабатывает 95% входящих обращений без участия человека.',
                Agent::FEATURES => [
                    'Голос и чат одновременно',
                    'Обучается на ваших данных',
                    'Эскалация сложных кейсов',
                ],
                Agent::ICON_NAME => 'message',
                Agent::SORT_ORDER => 3,
                Agent::IS_ACTIVE => true,
            ],
            [
                Agent::NAME => 'AI-Adaptologist',
                Agent::TAG => 'Обучение персонала',
                Agent::DESCRIPTION => 'Автоматический онбординг сотрудников, тестирование знаний и адаптация к стандартам компании за 5 дней вместо 3 недель.',
                Agent::FEATURES => [
                    'Адаптация за 5 дней',
                    '−70% нагрузки на HR',
                    'Автотестирование и аттестации',
                ],
                Agent::ICON_NAME => 'box',
                Agent::SORT_ORDER => 4,
                Agent::IS_ACTIVE => true,
            ],
        ];

        foreach ($agents as $agent) {
            Agent::query()->updateOrCreate(
                [Agent::NAME => $agent[Agent::NAME]],
                $agent
            );
        }
    }
}
