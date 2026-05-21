<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Partner\PartnerBenefitIconEnum;
use App\Enums\Partner\PartnerVariantTypeEnum;
use App\Models\PartnerBenefit;
use App\Models\PartnerStep;
use App\Models\PartnerVariant;
use Illuminate\Database\Seeder;

final class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $variants = [
            [
                PartnerVariant::TYPE => PartnerVariantTypeEnum::DEVELOPMENT,
                PartnerVariant::TITLE => 'Разработка продукта',
                PartnerVariant::DESCRIPTION => 'Партнёр получает процент от стоимости разработки, которую оплачивает клиент. Разовый платёж — сразу после закрытия сделки.',
                PartnerVariant::PERCENTAGE => 20,
                PartnerVariant::MIN_AMOUNT => 100000,
                PartnerVariant::AMOUNT_LABEL => 'от 100 тыс ₽',
                PartnerVariant::BADGE_COLOR => '#005FAA',
                PartnerVariant::BADGE_BG => 'rgba(0,207,255,0.08)',
                PartnerVariant::TAGS => ['Голосовые роботы', 'Чат-боты', 'AI-агенты'],
                PartnerVariant::SORT_ORDER => 1,
                PartnerVariant::IS_ACTIVE => true,
            ],
            [
                PartnerVariant::TYPE => PartnerVariantTypeEnum::SUBSCRIPTION,
                PartnerVariant::TITLE => 'Подписка клиента',
                PartnerVariant::DESCRIPTION => 'Партнёр получает процент от первого платежа клиента по подписке. Выплата сразу после оплаты клиентом — без ожидания.',
                PartnerVariant::PERCENTAGE => 20,
                PartnerVariant::MIN_AMOUNT => 30000,
                PartnerVariant::AMOUNT_LABEL => 'от 30 тыс ₽/мес',
                PartnerVariant::BADGE_COLOR => '#7C3AED',
                PartnerVariant::BADGE_BG => 'rgba(167,139,250,0.1)',
                PartnerVariant::TAGS => ['AI-Consultant', 'AI-LeadGen', 'AI-Manager'],
                PartnerVariant::SORT_ORDER => 2,
                PartnerVariant::IS_ACTIVE => true,
            ],
        ];

        foreach ($variants as $variant) {
            PartnerVariant::query()->updateOrCreate(
                [PartnerVariant::TYPE => $variant[PartnerVariant::TYPE]->value],
                $variant
            );
        }

        // Partner Steps
        $steps = [
            [
                PartnerStep::NUMBER => 1,
                PartnerStep::TITLE => 'Передайте контакт',
                PartnerStep::DESCRIPTION => 'Знаете бизнес, которому нужна AI-автоматизация? Поделитесь контактом — мы свяжемся сами.',
                PartnerStep::SORT_ORDER => 1,
                PartnerStep::IS_ACTIVE => true,
            ],
            [
                PartnerStep::NUMBER => 2,
                PartnerStep::TITLE => 'Мы проводим бесплатный аудит',
                PartnerStep::DESCRIPTION => 'Наши специалисты анализируют процессы клиента, подбирают продукт и рассчитывают ROI.',
                PartnerStep::SORT_ORDER => 2,
                PartnerStep::IS_ACTIVE => true,
            ],
            [
                PartnerStep::NUMBER => 3,
                PartnerStep::TITLE => 'Закрываем сделку и внедряем',
                PartnerStep::DESCRIPTION => 'Полная разработка и интеграция — без вашего участия. Всё под ключ за 14 дней.',
                PartnerStep::SORT_ORDER => 3,
                PartnerStep::IS_ACTIVE => true,
            ],
            [
                PartnerStep::NUMBER => 4,
                PartnerStep::TITLE => 'Выплата после оплаты клиентом',
                PartnerStep::DESCRIPTION => 'Получаете партнёрский % сразу после того, как клиент оплатил. Прозрачно и без задержек.',
                PartnerStep::SORT_ORDER => 4,
                PartnerStep::IS_ACTIVE => true,
            ],
        ];

        foreach ($steps as $step) {
            PartnerStep::query()->updateOrCreate(
                [PartnerStep::NUMBER => $step[PartnerStep::NUMBER]],
                $step
            );
        }

        // Partner Benefits
        $benefits = [
            [
                PartnerBenefit::TITLE => 'Без вложений и рисков',
                PartnerBenefit::DESCRIPTION => 'Вы только передаёте контакт. Продажи, внедрение, поддержка — всё на нас.',
                PartnerBenefit::ICON_NAME => PartnerBenefitIconEnum::CHECK,
                PartnerBenefit::SORT_ORDER => 1,
                PartnerBenefit::IS_ACTIVE => true,
            ],
            [
                PartnerBenefit::TITLE => 'Быстрые выплаты',
                PartnerBenefit::DESCRIPTION => 'Выплата сразу после оплаты клиентом — никаких задержек и бюрократии.',
                PartnerBenefit::ICON_NAME => PartnerBenefitIconEnum::CLOCK,
                PartnerBenefit::SORT_ORDER => 2,
                PartnerBenefit::IS_ACTIVE => true,
            ],
            [
                PartnerBenefit::TITLE => 'Поддержка на каждом этапе',
                PartnerBenefit::DESCRIPTION => 'Персональный менеджер, помощь с презентацией клиенту и ответы на все вопросы.',
                PartnerBenefit::ICON_NAME => PartnerBenefitIconEnum::USERS,
                PartnerBenefit::SORT_ORDER => 3,
                PartnerBenefit::IS_ACTIVE => true,
            ],
            [
                PartnerBenefit::TITLE => 'Высокая конверсия',
                PartnerBenefit::DESCRIPTION => 'Бесплатный аудит снижает барьер для клиента — большинство аудитов заканчиваются сделкой.',
                PartnerBenefit::ICON_NAME => PartnerBenefitIconEnum::TRENDING_UP,
                PartnerBenefit::SORT_ORDER => 4,
                PartnerBenefit::IS_ACTIVE => true,
            ],
        ];

        foreach ($benefits as $benefit) {
            PartnerBenefit::query()->updateOrCreate(
                [PartnerBenefit::TITLE => $benefit[PartnerBenefit::TITLE]],
                $benefit
            );
        }
    }
}
