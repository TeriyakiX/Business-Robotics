<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

final class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ========== HERO SECTION ==========
            ['key' => 'hero_title_line_1', 'value' => 'Автоматизируйте', 'group' => 'hero', 'type' => 'text'],
            ['key' => 'hero_title_line_2', 'value' => 'рабочие процессы', 'group' => 'hero', 'type' => 'text'],
            ['key' => 'hero_title_line_3', 'value' => 'с AI-агентами.', 'group' => 'hero', 'type' => 'text'],
            ['key' => 'hero_eyebrow', 'value' => 'AI-автоматизация нового поколения', 'group' => 'hero', 'type' => 'text'],
            ['key' => 'hero_button_text', 'value' => 'Попробовать демо-версию', 'group' => 'hero', 'type' => 'text'],
            ['key' => 'hero_use_spline', 'value' => 'true', 'group' => 'hero', 'type' => 'boolean'],
            ['key' => 'hero_background', 'value' => null, 'group' => 'hero', 'type' => 'image'],
            ['key' => 'hero_media', 'value' => null, 'group' => 'hero', 'type' => 'file'],
            ['key' => 'hero_media_type', 'value' => null, 'group' => 'hero', 'type' => 'text'],
            // Левый верхний текст в Hero
            ['key' => 'hero_top_text', 'value' => 'Роботы Business Robotics принимают звонки, записывают клиентов и квалифицируют лиды — 24/7, без ошибок и выходных.', 'group' => 'hero', 'type' => 'text'],

            // ========== AGENTS SECTION ==========
            ['key' => 'agents_title', 'value' => 'AI-агенты для каждой задачи', 'group' => 'agents', 'type' => 'text'],
            ['key' => 'agents_subtitle', 'value' => 'Каждый агент — специализированный алгоритм, обученный под конкретный бизнес-процесс', 'group' => 'agents', 'type' => 'text'],

            // ========== CASES SECTION ==========
            ['key' => 'cases_pill', 'value' => 'Кейсы', 'group' => 'cases', 'type' => 'text'],
            ['key' => 'cases_title', 'value' => 'Реальные', 'group' => 'cases', 'type' => 'text'],
            ['key' => 'cases_title_highlight', 'value' => 'результаты', 'group' => 'cases', 'type' => 'text'],
            ['key' => 'cases_subtitle', 'value' => 'Как Business Robotics помог бизнесам сократить расходы и увеличить продажи', 'group' => 'cases', 'type' => 'text'],
            ['key' => 'cases_more_button', 'value' => 'Смотреть ещё кейсы', 'group' => 'cases', 'type' => 'text'],
            ['key' => 'cases_hide_button', 'value' => 'Скрыть кейсы', 'group' => 'cases', 'type' => 'text'],

            // ========== PROCESS SECTION ==========
            ['key' => 'process_title', 'value' => 'Запуск за 14 дней', 'group' => 'process', 'type' => 'text'],
            ['key' => 'process_subtitle', 'value' => 'От консультации до полноценной работы агента — без сложностей', 'group' => 'process', 'type' => 'text'],

            // ========== BLOG SECTION ==========
            ['key' => 'blog_title', 'value' => 'Мир роботов', 'group' => 'blog', 'type' => 'text'],
            ['key' => 'blog_subtitle', 'value' => 'Последние разработки в сфере роботехники и AI — только важное', 'group' => 'blog', 'type' => 'text'],

            // ========== PARTNERS SECTION ==========
            // Заголовки секции
            ['key' => 'partners_pill', 'value' => 'Партнёрам', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partners_title', 'value' => 'Зарабатывайте вместе с Business Robotics', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partners_subtitle', 'value' => 'Приводите клиентов — мы закрываем всё остальное. Получайте % с каждой сделки без вложений.', 'group' => 'partners', 'type' => 'text'],

            // Карточка 1 (Разработка)
            ['key' => 'partner_variant1_badge', 'value' => 'Вариант 1', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant1_title', 'value' => 'Разработка продукта', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant1_desc', 'value' => 'Партнёр получает процент от стоимости разработки, которую оплачивает клиент. Разовый платёж — сразу после закрытия сделки.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant1_percent', 'value' => 'до 20%', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant1_percent_label', 'value' => 'от суммы разработки', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant1_amount_label', 'value' => 'Чек разработки', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant1_amount_value', 'value' => 'от 100 тыс ₽', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant1_tags', 'value' => json_encode(['Голосовые роботы', 'Чат-боты', 'AI-агенты']), 'group' => 'partners', 'type' => 'json'],

            // Карточка 2 (Подписка)
            ['key' => 'partner_variant2_badge', 'value' => 'Вариант 2', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant2_title', 'value' => 'Подписка клиента', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant2_desc', 'value' => 'Партнёр получает процент от первого платежа клиента по подписке. Выплата сразу после оплаты клиентом — без ожидания.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant2_percent', 'value' => 'до 20%', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant2_percent_label', 'value' => 'от первого платежа', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant2_amount_label', 'value' => 'Первый платёж', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant2_amount_value', 'value' => 'от 30 тыс ₽/мес', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_variant2_tags', 'value' => json_encode(['AI-Consultant', 'AI-LeadGen', 'AI-Manager']), 'group' => 'partners', 'type' => 'json'],

            // Earn карточка
            ['key' => 'partner_earn_min_label', 'value' => 'Мин. доход с клиента', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_earn_min_value', 'value' => 'от 20 000 ₽', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_earn_min_note', 'value' => 'разовая выплата', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_earn_top_label', 'value' => 'Доход с флагмана', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_earn_top_value', 'value' => 'сотни тыс ₽', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_earn_top_note', 'value' => 'за одну сделку', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_earn_audit_label', 'value' => 'Аудит для клиента', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_earn_audit_value', 'value' => 'бесплатно', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_earn_audit_note', 'value' => 'мы берём расходы на себя', 'group' => 'partners', 'type' => 'text'],

            // Шаги программы
            ['key' => 'partner_steps_title', 'value' => 'Как работает программа', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step1_num', 'value' => '1', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step1_title', 'value' => 'Передайте контакт', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step1_desc', 'value' => 'Знаете бизнес, которому нужна AI-автоматизация? Поделитесь контактом — мы свяжемся сами.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step2_num', 'value' => '2', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step2_title', 'value' => 'Мы проводим бесплатный аудит', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step2_desc', 'value' => 'Наши специалисты анализируют процессы клиента, подбирают продукт и рассчитывают ROI.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step3_num', 'value' => '3', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step3_title', 'value' => 'Закрываем сделку и внедряем', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step3_desc', 'value' => 'Полная разработка и интеграция — без вашего участия. Всё под ключ за 14 дней.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step4_num', 'value' => '4', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step4_title', 'value' => 'Выплата после оплаты клиентом', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_step4_desc', 'value' => 'Получаете партнёрский % сразу после того, как клиент оплатил. Прозрачно и без задержек.', 'group' => 'partners', 'type' => 'text'],

            // Почему выбирают нас
            ['key' => 'partner_why_title', 'value' => 'Почему партнёры выбирают нас', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_why1_title', 'value' => 'Без вложений и рисков', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_why1_desc', 'value' => 'Вы только передаёте контакт. Продажи, внедрение, поддержка — всё на нас.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_why2_title', 'value' => 'Быстрые выплаты', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_why2_desc', 'value' => 'Выплата сразу после оплаты клиентом — никаких задержек и бюрократии.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_why3_title', 'value' => 'Поддержка на каждом этапе', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_why3_desc', 'value' => 'Персональный менеджер, помощь с презентацией клиенту и ответы на все вопросы.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_why4_title', 'value' => 'Высокая конверсия', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partner_why4_desc', 'value' => 'Бесплатный аудит снижает барьер для клиента — большинство аудитов заканчиваются сделкой.', 'group' => 'partners', 'type' => 'text'],

            // CTA партнёров
            ['key' => 'partners_cta_label', 'value' => 'Станьте партнёром', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partners_cta_title', 'value' => 'Готовы начать зарабатывать?', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partners_cta_desc', 'value' => 'Передайте первый контакт — мы проведём аудит, подберём продукт и закроем внедрение. Аудит бесплатный.', 'group' => 'partners', 'type' => 'text'],
            ['key' => 'partners_cta_button', 'value' => 'Стать партнёром', 'group' => 'partners', 'type' => 'text'],

            // ========== CTA SECTION ==========
            ['key' => 'cta_pill', 'value' => 'Начните сегодня', 'group' => 'cta', 'type' => 'text'],
            ['key' => 'cta_title', 'value' => 'Автоматизируйте свой бизнес', 'group' => 'cta', 'type' => 'text'],
            ['key' => 'cta_subtitle', 'value' => 'Получите бесплатную демонстрацию и расчёт ROI. Без обязательств — просто увидите результат.', 'group' => 'cta', 'type' => 'text'],
            ['key' => 'cta_button_text', 'value' => 'Получить бесплатное демо', 'group' => 'cta', 'type' => 'text'],
            ['key' => 'cta_button_telegram', 'value' => 'Написать в Telegram', 'group' => 'cta', 'type' => 'text'],
            ['key' => 'cta_note', 'value' => 'Ответим в течение 2 часов в рабочее время', 'group' => 'cta', 'type' => 'text'],

            // ========== CONTACT FORM ==========
            ['key' => 'contact_form_pill', 'value' => 'Бесплатное демо', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_title', 'value' => 'Запросить демо', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_subtitle', 'value' => 'Заполните форму — свяжемся в течение 2 часов.', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_name_label', 'value' => 'Ваше имя', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_phone_label', 'value' => 'Номер телефона', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_company_label', 'value' => 'Название компании', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_submit_text', 'value' => 'Отправить заявку', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_success_title', 'value' => 'Заявка отправлена!', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_success_message', 'value' => 'Свяжемся с вами в течение 2 часов в рабочее время.', 'group' => 'contact_form', 'type' => 'text'],
            ['key' => 'contact_form_privacy_note', 'value' => 'Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности', 'group' => 'contact_form', 'type' => 'text'],

            // ========== FOOTER ==========
            ['key' => 'footer_brand_name', 'value' => 'Business Robotics', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_brand_desc', 'value' => 'AI-агенты для автоматизации обзвона и бизнес-процессов нового поколения.', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_products_title', 'value' => 'Продукты', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_company_title', 'value' => 'Компания', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_contacts_title', 'value' => 'Контакты', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_phone', 'value' => '8 800 123-45-67', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_email', 'value' => 'hello@biz-robotics.ru', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_telegram', 'value' => 'https://t.me/bizroboticsbot', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_copyright', 'value' => '© 2026 Business Robotics. Все права защищены.', 'group' => 'footer', 'type' => 'text'],

            // ========== SOCIALS ==========
            ['key' => 'socials', 'value' => json_encode([
                ['name' => 'Telegram', 'url' => 'https://t.me/bizroboticsbot', 'icon' => 'telegram'],
                ['name' => 'Email', 'url' => 'mailto:hello@biz-robotics.ru', 'icon' => 'mail'],
                ['name' => 'WhatsApp', 'url' => 'https://wa.me/78001234567', 'icon' => 'whatsapp'],
                ['name' => 'VK', 'url' => 'https://vk.com/bizrobotics', 'icon' => 'vk'],
            ]), 'group' => 'socials', 'type' => 'json'],

            // ========== CONTACTS ==========
            ['key' => 'contact_phone', 'value' => '8 800 123-45-67', 'group' => 'contacts', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'hello@biz-robotics.ru', 'group' => 'contacts', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'Москва, ул. Примерная, д. 1', 'group' => 'contacts', 'type' => 'text'],

            // ========== MARQUEE ==========
            ['key' => 'marquee_items', 'value' => json_encode([
                'Битрикс24', 'AmoCRM', 'Telegram', '1С', 'Salesforce',
                'WhatsApp Business', 'Asterisk', 'Mango Office', 'Zoom Phone',
                'Google Workspace', 'Slack', 'Notion'
            ]), 'group' => 'marquee', 'type' => 'json'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
