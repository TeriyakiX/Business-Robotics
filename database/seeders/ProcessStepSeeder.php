<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProcessStep;
use Illuminate\Database\Seeder;

final class ProcessStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                ProcessStep::NUMBER => 1,
                ProcessStep::TITLE => 'Анализ и консультация',
                ProcessStep::DESCRIPTION => 'Изучаем бизнес-процессы, определяем точки автоматизации и рассчитываем ROI до начала работ.',
                ProcessStep::DAY_RANGE => 'День 1–2',
                ProcessStep::SORT_ORDER => 1,
                ProcessStep::IS_ACTIVE => true,
            ],
            [
                ProcessStep::NUMBER => 2,
                ProcessStep::TITLE => 'Разработка агента',
                ProcessStep::DESCRIPTION => 'Создаём и настраиваем AI-агента под ваши скрипты, базу знаний и фирменный голос.',
                ProcessStep::DAY_RANGE => 'День 3–8',
                ProcessStep::SORT_ORDER => 2,
                ProcessStep::IS_ACTIVE => true,
            ],
            [
                ProcessStep::NUMBER => 3,
                ProcessStep::TITLE => 'Интеграция и тест',
                ProcessStep::DESCRIPTION => 'Подключаем к CRM, телефонии, мессенджерам. Проводим тестирование на реальных сценариях.',
                ProcessStep::DAY_RANGE => 'День 9–12',
                ProcessStep::SORT_ORDER => 3,
                ProcessStep::IS_ACTIVE => true,
            ],
            [
                ProcessStep::NUMBER => 4,
                ProcessStep::TITLE => 'Запуск и поддержка',
                ProcessStep::DESCRIPTION => 'Запускаем в продакшн, обеспечиваем мониторинг, техподдержку 24/7 и непрерывное улучшение.',
                ProcessStep::DAY_RANGE => 'День 13–14 →',
                ProcessStep::SORT_ORDER => 4,
                ProcessStep::IS_ACTIVE => true,
            ],
        ];

        foreach ($steps as $step) {
            ProcessStep::query()->updateOrCreate(
                [ProcessStep::NUMBER => $step[ProcessStep::NUMBER]],
                $step
            );
        }
    }
}
