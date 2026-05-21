<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Case\CaseIndustryEnum;
use App\Models\BusinessCase;
use Illuminate\Database\Seeder;

final class CaseSeeder extends Seeder
{
    public function run(): void
    {
        $cases = [
            [
                BusinessCase::TITLE => 'Стоматологическая клиника',
                BusinessCase::CLIENT_NAME => 'Алексей В.',
                BusinessCase::CLIENT_ROLE => 'Главный врач, сеть из 5 клиник',
                BusinessCase::CLIENT_AVATAR_INITIALS => 'А',
                BusinessCase::INDUSTRY => CaseIndustryEnum::MEDICINE,
                BusinessCase::METRICS => [
                    ['value' => '−80%', 'label' => 'времени на обучение'],
                    ['value' => '1.5M ₽', 'label' => 'экономия в год'],
                ],
                BusinessCase::DESCRIPTION => 'AI-Adaptologist сократил адаптацию стоматологов с 3 недель до 3 дней. Ручное тестирование полностью исключено.',
                BusinessCase::TESTIMONIAL => 'Отличный продукт! Рекомендую.',
                BusinessCase::SORT_ORDER => 1,
                BusinessCase::IS_VISIBLE => true,
            ],
            [
                BusinessCase::TITLE => 'Колл-центр',
                BusinessCase::CLIENT_NAME => 'Мария К.',
                BusinessCase::CLIENT_ROLE => 'Директор по продажам',
                BusinessCase::CLIENT_AVATAR_INITIALS => 'М',
                BusinessCase::INDUSTRY => CaseIndustryEnum::CALL_CENTER,
                BusinessCase::METRICS => [
                    ['value' => '3→5', 'label' => 'недели → дней'],
                    ['value' => '−70%', 'label' => 'нагрузки'],
                ],
                BusinessCase::DESCRIPTION => 'AI-LeadGen взял квалификацию заявок на себя. Конверсия из звонка в сделку выросла на 40% благодаря мгновенной обработке.',
                BusinessCase::TESTIMONIAL => 'Конверсия выросла на 40%!',
                BusinessCase::SORT_ORDER => 2,
                BusinessCase::IS_VISIBLE => true,
            ],
            [
                BusinessCase::TITLE => 'Сеть студий красоты',
                BusinessCase::CLIENT_NAME => 'Екатерина Л.',
                BusinessCase::CLIENT_ROLE => 'Владелец, 12 студий',
                BusinessCase::CLIENT_AVATAR_INITIALS => 'Е',
                BusinessCase::INDUSTRY => CaseIndustryEnum::BEAUTY,
                BusinessCase::METRICS => [
                    ['value' => '+35%', 'label' => 'рост записей'],
                    ['value' => '−60%', 'label' => 'неявок'],
                ],
                BusinessCase::DESCRIPTION => 'AI-Manager автоматизировал напоминания и перенос слотов. Администраторы освободились для живого общения с клиентами.',
                BusinessCase::TESTIMONIAL => 'Администраторы счастливы!',
                BusinessCase::SORT_ORDER => 3,
                BusinessCase::IS_VISIBLE => true,
            ],
            [
                BusinessCase::TITLE => 'Медицинский центр',
                BusinessCase::CLIENT_NAME => 'Дмитрий П.',
                BusinessCase::CLIENT_ROLE => 'Главный врач, сеть клиник',
                BusinessCase::CLIENT_AVATAR_INITIALS => 'Д',
                BusinessCase::INDUSTRY => CaseIndustryEnum::MEDICINE,
                BusinessCase::METRICS => [
                    ['value' => '5 000', 'label' => 'обзвонов в месяц'],
                    ['value' => '−65%', 'label' => 'затрат на колл-центр'],
                ],
                BusinessCase::DESCRIPTION => 'AI-LeadGen автоматизировал обзвон базы из 20 000 пациентов. Робот самостоятельно напоминал о профосмотрах и записывал на приём — без единого оператора.',
                BusinessCase::TESTIMONIAL => 'Экономия огромная!',
                BusinessCase::SORT_ORDER => 4,
                BusinessCase::IS_VISIBLE => true,
            ],
            [
                BusinessCase::TITLE => 'Сеть фитнес-клубов',
                BusinessCase::CLIENT_NAME => 'Сергей М.',
                BusinessCase::CLIENT_ROLE => 'Директор по развитию, 8 клубов',
                BusinessCase::CLIENT_AVATAR_INITIALS => 'С',
                BusinessCase::INDUSTRY => CaseIndustryEnum::FITNESS,
                BusinessCase::METRICS => [
                    ['value' => '+42%', 'label' => 'возврат клиентов'],
                    ['value' => '−55%', 'label' => 'пропущенных тренировок'],
                ],
                BusinessCase::DESCRIPTION => 'AI-Manager записывал клиентов на групповые занятия и отправлял голосовые напоминания за 2 часа до тренировки. Количество пропусков сократилось вдвое.',
                BusinessCase::TESTIMONIAL => 'Посещаемость выросла!',
                BusinessCase::SORT_ORDER => 5,
                BusinessCase::IS_VISIBLE => true,
            ],
            [
                BusinessCase::TITLE => 'Юридическая компания',
                BusinessCase::CLIENT_NAME => 'Ольга В.',
                BusinessCase::CLIENT_ROLE => 'Управляющий партнёр',
                BusinessCase::CLIENT_AVATAR_INITIALS => 'О',
                BusinessCase::INDUSTRY => CaseIndustryEnum::LEGAL,
                BusinessCase::METRICS => [
                    ['value' => '−90%', 'label' => 'пропущенных встреч'],
                    ['value' => '800 ч', 'label' => 'сэкономлено в год'],
                ],
                BusinessCase::DESCRIPTION => 'AI-Manager автоматически подтверждал встречи с клиентами за день и за час до консультации. Юристы перестали тратить время на ручные напоминания.',
                BusinessCase::TESTIMONIAL => 'Время — деньги!',
                BusinessCase::SORT_ORDER => 6,
                BusinessCase::IS_VISIBLE => true,
            ],
        ];

        foreach ($cases as $case) {
            BusinessCase::query()->updateOrCreate(
                [BusinessCase::TITLE => $case[BusinessCase::TITLE]],
                $case
            );
        }
    }
}
